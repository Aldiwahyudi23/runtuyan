<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FamilyTree\Marriage;
use App\Models\FamilyTree\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::with(['spouses', 'parents', 'children'])
            ->orderBy('name')
            ->get();

        return Inertia::render('People/Index', [
            'people' => $people,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('People/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Person::where('name', $value)
                        ->where('birth_date', $request->birth_date)
                        ->where('gender', $request->gender)
                        ->exists();

                    if ($exists) {
                        $fail('Data dengan nama, tanggal lahir, dan jenis kelamin yang sama sudah ada.');
                    }
                }
            ],
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after:birth_date',
            'bio' => 'nullable|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus laki-laki atau perempuan.',

            'birth_date.date' => 'Tanggal lahir harus berupa format tanggal yang valid.',

            'death_date.date' => 'Tanggal wafat harus berupa format tanggal yang valid.',
            'death_date.after' => 'Tanggal wafat harus setelah tanggal lahir.',

            'bio.string' => 'Biografi harus berupa teks.',
        ]);


        Person::create($validated);

        return redirect()->route('people.index')->with('success', 'Person added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        $person->load([
            'spouses',
            'children',
            'parents',
            'relationships.relatedPerson',
            'inverseRelationships.person'
        ]);

        return Inertia::render('People/Show', [
            'person' => $person,
            'familyTree' => $this->getFamilyTree($person),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return Inertia::render('People/Edit', [
            'person' => $person,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $person) {
                    $exists = Person::where('name', $value)
                        ->where('birth_date', $request->birth_date)
                        ->where('gender', $request->gender)
                        ->where('id', '!=', $person->id) // Abaikan record saat ini
                        ->exists();

                    if ($exists) {
                        $fail('Data dengan nama, tanggal lahir, dan jenis kelamin yang sama sudah ada.');
                    }
                }
            ],
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after:birth_date',
            'bio' => 'nullable|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus laki-laki atau perempuan.',

            'birth_date.date' => 'Tanggal lahir harus berupa format tanggal yang valid.',

            'death_date.date' => 'Tanggal wafat harus berupa format tanggal yang valid.',
            'death_date.after' => 'Tanggal wafat harus setelah tanggal lahir.',

            'bio.string' => 'Biografi harus berupa teks.',
        ]);


        $person->update($validated);

        return redirect()->route('people.show', $person->id)->with('success', 'Person updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('people.index')->with('success', 'Person deleted successfully.');
    }

    // Helper untuk mendapatkan family tree
    private function getFamilyTree(Person $person, $depth = 3)
    {
        if ($depth <= 0) {
            return null;
        }

        $tree = [
            'person' => $person,
            'parents' => [],
            'spouses' => [],
            'children' => [],
        ];

        // Get parents
        foreach ($person->parents as $parent) {
            $tree['parents'][] = $this->getFamilyTree($parent, $depth - 1);
        }

        // Get spouses
        foreach ($person->spouses as $spouse) {
            $tree['spouses'][] = [
                'person' => $spouse,
                'marriage' => $spouse->pivot->marriage_id ? Marriage::find($spouse->pivot->marriage_id) : null,
            ];
        }

        // Get children
        foreach ($person->children as $child) {
            $tree['children'][] = $this->getFamilyTree($child, $depth - 1);
        }

        return $tree;
    }
}