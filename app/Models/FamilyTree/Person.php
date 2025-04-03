<?php

namespace App\Models\FamilyTree;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = "persons";

    protected $fillable = [
        'name',
        'gender',
        'birth_date',
        'death_date',
        'bio',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    /**
     * Get all relationships where this person is the subject
     */
    public function relationships()
    {
        return $this->hasMany(Relationship::class, 'person_id');
    }

    /**
     * Get all relationships where this person is the object
     */
    public function inverseRelationships()
    {
        return $this->hasMany(Relationship::class, 'related_person_id');
    }

    /**
     * Get all marriages where this person is involved
     */
    public function marriages()
    {
        return $this->belongsToMany(Marriage::class, 'relationships', 'person_id', 'marriage_id')
            ->where('type', 'spouse')
            ->orWhere('type', 'ex_spouse');
    }

    /**
     * Get the person's spouses
     */
    public function spouses()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'person_id', 'related_person_id')
            ->where('type', 'spouse')
            ->withPivot('marriage_id');
    }

    /**
     * Get the person's ex-spouses
     */
    public function exSpouses()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'person_id', 'related_person_id')
            ->where('type', 'ex_spouse')
            ->withPivot('marriage_id');
    }

    /**
     * Get the person's parents
     */
    public function parents()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'person_id', 'related_person_id')
            ->where('type', 'parent');
    }

    /**
     * Get the person's children
     */
    public function children()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'person_id', 'related_person_id')
            ->where('type', 'child');
    }

    /**
     * Check if the person is alive
     */
    public function isAlive(): bool
    {
        return is_null($this->death_date);
    }

    /**
     * Get the person's age
     */

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        $endDate = $this->death_date ? new \DateTime($this->death_date) : now();
        return $endDate->diff(new \DateTime($this->birth_date))->y;
    }
}