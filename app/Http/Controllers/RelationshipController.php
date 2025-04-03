<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FamilyTree\Marriage;
use App\Models\FamilyTree\Person;
use App\Models\FamilyTree\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RelationshipController extends Controller
{
    // Di PeopleController atau RelationshipsController
    public function create(Request $request)
    {
        $person = Person::with(['relationships' => function ($query) {
            $query->with(['relatedPerson', 'marriage']);
        }])->findOrFail($request->input('person_id'));

        $existingRelationships = $person->relationships->map(function ($rel) {
            return [
                'id' => $rel->id,
                'type' => $rel->type,
                'related_person' => $rel->relatedPerson,
                'marriage_id' => $rel->marriage_id,
                'marriage' => $rel->marriage
            ];
        });

        return Inertia::render('Relationships/Create', [
            'person' => $person,
            'people' => Person::where('id', '!=', $person->id)->get(),
            'existingRelationships' => $existingRelationships
        ]);
    }


    // Menyimpan hubungan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:persons,id',
            'related_person_id' => 'required|exists:persons,id|different:person_id',
            'type' => 'required|in:parent,child,spouse,ex_spouse',
            'marriage_date' => 'nullable|required_if:type,spouse,ex_spouse|date',
            'parent_id' => [
                'required_if:type,child',
                'integer',
                'nullable',
                'exists:persons,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'child' && $value) {
                        $validSpouse = Relationship::where('person_id', $request->person_id)
                            ->where('related_person_id', $value)
                            ->whereIn('type', ['spouse', 'ex_spouse'])
                            ->exists();

                        if (!$validSpouse) {
                            $fail('Orang yang dipilih bukan pasangan/mantan pasangan yang valid.');
                        }
                    }
                }
            ],
            'second_parent_id' => [
                'required_if:type,parent',
                'nullable',
                'integer',
                'exists:persons,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'parent' && $value) {
                        // Pastikan second_parent berlawanan gender dengan person
                        $person = Person::find($request->person_id);
                        $secondParent = Person::find($value);

                        if ($person->gender === $secondParent->gender) {
                            $fail('Orang tua kedua harus berlawanan jenis kelamin.');
                        }
                    }
                }
            ]
        ], [
            'person_id.required' => 'Orang yang dipilih wajib diisi.',
            'person_id.exists' => 'Orang yang dipilih tidak ditemukan dalam database.',

            'related_person_id.required' => 'Orang yang berhubungan wajib diisi.',
            'related_person_id.exists' => 'Orang yang berhubungan tidak ditemukan dalam database.',
            'related_person_id.different' => 'Orang yang dipilih tidak boleh sama dengan orang yang berhubungan.',

            'type.required' => 'Tipe hubungan wajib dipilih.',
            'type.in' => 'Tipe hubungan harus salah satu dari: orang tua, anak, pasangan, atau mantan pasangan.',

            'marriage_date.required_if' => 'Tanggal pernikahan wajib diisi jika hubungan adalah pasangan atau mantan pasangan.',
            'marriage_date.date' => 'Tanggal pernikahan harus dalam format tanggal yang valid.',
        ]);

        // Mulai database transaction
        // DB::beginTransaction();

        // try {
        $person = Person::find($validated['person_id']);
        $relatedPerson = Person::find($validated['related_person_id']);

        // Validasi gender untuk pasangan
        if (in_array($validated['type'], ['spouse', 'ex_spouse'])) {
            if ($person->gender === $relatedPerson->gender) {
                return back()->withErrors([
                    'related_person_id' => 'Pasangan harus berlawanan jenis kelamin.'
                ]);
            }

            // Untuk wanita, cek apakah sudah memiliki pasangan
            if ($person->gender === 'female' && $validated['type'] === 'spouse') {
                $existingSpouse = Relationship::where('person_id', $person->id)
                    ->where('type', 'spouse')
                    ->first();

                if ($existingSpouse) {
                    return back()->withErrors([
                        'type' => 'Wanita ini sudah memiliki pasangan. Tambahkan sebagai mantan pasangan terlebih dahulu.'
                    ]);
                }
            }
        }

        $marriage = null;
        if (in_array($validated['type'], ['spouse', 'ex_spouse'])) {
            $marriage = Marriage::create([
                'marriage_date' => $validated['marriage_date'],
            ]);
        }

        // Create relationship in both directions if needed
        $relationship = Relationship::create([
            'person_id' => $validated['person_id'],
            'related_person_id' => $validated['related_person_id'],
            'type' => $validated['type'],
            'marriage_id' => $marriage?->id,
        ]);
        // Create inverse relationship if needed
        if ($validated['type'] === 'parent') {
            Relationship::create([
                'person_id' => $validated['related_person_id'],
                'related_person_id' => $validated['person_id'],
                'type' => 'child',
                'marriage_id' => $marriage?->id,
            ]);
            if ($request->second_parent_id) {
                $parentId =  $request->second_parent_id;
                // Validasi second_parent_id
                if (!is_numeric($parentId)) {
                    return back()->withErrors([
                        'second_parent_id' => "ID Orang Tua tidak valid. {$parentId}"
                    ]);
                }

                // Pastikan second_parent_id adalah pasangan/mantan pasangan yang valid
                $validSpouse = Relationship::where('person_id', $validated['person_id'])
                    ->where('related_person_id', $parentId)
                    ->whereIn('type', ['spouse', 'ex_spouse'])
                    ->exists();

                if (!$validSpouse) {
                    return back()->withErrors([
                        'second_parent_id' => 'Orang yang dipilih bukan pasangan/mantan pasangan yang valid.'
                    ]);
                }

                // Buat hubungan antara anak dan pasangan yang dipilih
                Relationship::create([
                    'person_id' => $parentId,
                    'related_person_id' => $validated['related_person_id'],
                    'type' => 'parent',
                    'marriage_id' => null,
                ]);

                // Buat hubungan sebaliknya (anak ke pasangan sebagai orang tua)
                Relationship::create([
                    'person_id' => $validated['related_person_id'],
                    'related_person_id' => $parentId,
                    'type' => 'child',
                    'marriage_id' => null,
                ]);
            }
        } elseif ($validated['type'] === 'child') {
            Relationship::create([
                'person_id' => $validated['related_person_id'],
                'related_person_id' => $validated['person_id'],
                'type' => 'parent',
                'marriage_id' => $marriage?->id,
            ]);
            // Jika ini adalah anak dan dipilih pasangan sebagai parent
            if ($request->parent_id) {
                $parentId =  $request->parent_id;
                // Validasi parent_id
                if (!is_numeric($parentId)) {
                    return back()->withErrors([
                        'parent_id' => "ID pasangan tidak valid. {$parentId}"
                    ]);
                }

                // Pastikan parent_id adalah pasangan/mantan pasangan yang valid
                $validSpouse = Relationship::where('person_id', $validated['person_id'])
                    ->where('related_person_id', $parentId)
                    ->whereIn('type', ['spouse', 'ex_spouse'])
                    ->exists();

                if (!$validSpouse) {
                    return back()->withErrors([
                        'parent_id' => 'Orang yang dipilih bukan pasangan/mantan pasangan yang valid.'
                    ]);
                }

                // Buat hubungan antara anak dan pasangan yang dipilih
                Relationship::create([
                    'person_id' => $parentId,
                    'related_person_id' => $validated['related_person_id'],
                    'type' => 'child',
                    'marriage_id' => null,
                ]);

                // Buat hubungan sebaliknya (anak ke pasangan sebagai orang tua)
                Relationship::create([
                    'person_id' => $validated['related_person_id'],
                    'related_person_id' => $parentId,
                    'type' => 'parent',
                    'marriage_id' => null,
                ]);
            }
        } elseif (in_array($validated['type'], ['spouse', 'ex_spouse'])) {
            Relationship::create([
                'person_id' => $validated['related_person_id'],
                'related_person_id' => $validated['person_id'],
                'type' => $validated['type'],
                'marriage_id' => $marriage?->id,
            ]);
        }
        // Jika semua berhasil, commit transaction
        // DB::commit();

        return redirect()->route('people.show', $validated['person_id'])
            ->with('success', 'Relationship added successfully.');
        // } catch (\Exception $e) {
        //     // Rollback transaction jika ada error
        //     DB::rollBack();

        //     return back()->withErrors([
        //         'system' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        //     ]);
        // }
    }

    // Hapus hubungan
    public function destroy(Relationship $relationship)
    {
        $personId = $relationship->person_id;
        // Delete inverse relationship if exists
        $inverseType = match ($relationship->type) {
            'parent' => 'child',
            'child' => 'parent',
            'spouse' => 'spouse',
            'ex_spouse' => 'ex_spouse',
            default => null,
        };

        if ($inverseType) {
            Relationship::where('person_id', $relationship->related_person_id)
                ->where('related_person_id', $relationship->person_id)
                ->where('type', $inverseType)
                ->delete();
        }

        $relationship->delete();

        return redirect()->route('people.show', $personId)
            ->with('success', 'Relationship removed successfully.');
    }
    private function getInverseType($type)
    {
        return match ($type) {
            'parent' => 'child',
            'child' => 'parent',
            'spouse' => 'spouse',
            'ex_spouse' => 'ex_spouse',
            default => $type,
        };
    }
}