<?php

namespace App\Http\Controllers\FamilyTree;

use App\Models\FamilyTree\Person;
use App\Http\Controllers\Controller;
use App\Models\FamilyTree\Marriage;
use App\Models\FamilyTree\Relationship;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Family/Index', [
            'persons' => Person::with(['parents', 'spouses', 'children'])->get(),
            'marriages' => Marriage::with('relationships')->get() // Tambahkan with('relationships')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $person = Person::create($request->only([
            'name',
            'gender',
            'birth_date'
        ]));

        // Jika ada orangtua
        if ($request->parent_id) {
            Relationship::create([
                'person_id' => $person->id,
                'related_person_id' => $request->parent_id,
                'type' => 'child'
            ]);
        }

        return response()->json($person);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        //
    }

    // Menyimpan data individu baru
    public function storePerson(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date'
        ]);

        $person = Person::create($request->only([
            'name',
            'gender',
            'birth_date',
            'death_date',
            'bio'
        ]));

        return redirect()->back()->with('success', 'Individu berhasil ditambahkan');
    }

    // Menyimpan data pernikahan
    public function storeMarriage(Request $request)
    {
        $request->validate([
            'person1_id' => [
                'required',
                'exists:persons,id',
                function ($attribute, $value, $fail) use ($request) {
                    // Cek apakah suami sudah menikah dan belum bercerai
                    $existingMarriage = Relationship::where('person_id', $value)
                        ->where('type', 'spouse')
                        ->whereDoesntHave('marriage', function ($q) {
                            $q->whereNotNull('divorce_date');
                        })->exists();

                    if ($existingMarriage) {
                        $fail('Orang ini sudah memiliki pasangan yang sah');
                    }
                }
            ],
            // ... validasi lainnya ...
        ]);


        $marriage = Marriage::create([
            'marriage_date' => $request->marriage_date
        ]);

        Relationship::create([
            'person_id' => $request->person1_id,
            'related_person_id' => $request->person2_id,
            'type' => 'spouse',
            'marriage_id' => $marriage->id
        ]);

        Relationship::create([
            'person_id' => $request->person2_id,
            'related_person_id' => $request->person1_id,
            'type' => 'spouse',
            'marriage_id' => $marriage->id
        ]);

        return redirect()->back()->with('success', 'Pernikahan berhasil dicatat');
    }

    // Menghubungkan orangtua-anak
    public function addParentChild(Request $request)
    {

        $request->validate([
            'parent_id' => [
                'required',
                'exists:persons,id',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Relationship::where('person_id', $request->child_id)
                        ->where('related_person_id', $value)
                        ->where('type', 'parent')
                        ->exists();

                    if ($exists) {
                        $fail('Hubungan orangtua-anak ini sudah tercatat');
                    }
                }
            ],
            // ... validasi lainnya ...
        ]);

        Relationship::create([
            'person_id' => $request->child_id,
            'related_person_id' => $request->parent_id,
            'type' => 'parent'
        ]);

        Relationship::create([
            'person_id' => $request->parent_id,
            'related_person_id' => $request->child_id,
            'type' => 'child'
        ]);

        return redirect()->back()->with('success', 'Hubungan keluarga berhasil ditambahkan');
    }

    public function data()
    {
        $persons = Person::all();

        return Inertia::render('FamilyTree/Index', [
            'people' => Person::all(),
            'relationships' => Relationship::with(['marriage'])->get(),
        ]);
    }
}