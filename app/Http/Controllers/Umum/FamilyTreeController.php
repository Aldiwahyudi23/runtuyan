<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\FamilyTree\Marriage;
use App\Models\FamilyTree\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FamilyTreeController extends Controller
{
    public function index()
    {
        return Inertia::render('Umum/FamilyTree/Index');
    }

    /**
     * Search for family members
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->input('query');

        $results = Person::where('name', 'LIKE', "%{$query}%")
            ->select(['id', 'name', 'birth_date', 'death_date', 'gender'])
            ->limit(10)
            ->get();

        // Untuk API request (axios/fetch)
        if ($request->wantsJson()) {
            return response()->json($results);
        }

        // Untuk Inertia request (jika diperlukan)
        return inertia('FamilyTree/SearchResults', [
            'results' => $results
        ]);
    }

    /**
     * Show a person's public profile and family tree
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

        return Inertia::render('Umum/FamilyTree/Show', [
            'person' => $person,
            'familyTree' => $this->getFamilyTree($person),
        ]);
    }

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
