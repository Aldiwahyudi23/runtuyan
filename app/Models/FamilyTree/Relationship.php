<?php

namespace App\Models\FamilyTree;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'related_person_id',
        'type',
        'marriage_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Get the person who is the object of this relationship
     */
    public function relatedPerson()
    {
        return $this->belongsTo(Person::class, 'related_person_id');
    }

    /**
     * Get the marriage associated with this relationship (if any)
     */
    public function marriage()
    {
        return $this->belongsTo(Marriage::class);
    }

    /**
     * Get the human-readable relationship type
     */
    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'parent' => 'Parent',
            'child' => 'Child',
            'spouse' => 'Spouse',
            'ex_spouse' => 'Ex-Spouse',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}