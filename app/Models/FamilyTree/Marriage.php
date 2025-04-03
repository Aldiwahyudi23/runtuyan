<?php

namespace App\Models\FamilyTree;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    use HasFactory;

    protected $fillable = [
        'marriage_date',
        'divorce_date',
        'notes',
    ];

    protected $casts = [
        'marriage_date' => 'date',
        'divorce_date' => 'date',
    ];
    /**
     * Get the spouses in this marriage
     */
    public function spouses()
    {
        return $this->hasMany(Relationship::class, 'marriage_id')
            ->where(function ($query) {
                $query->where('type', 'spouse')
                    ->orWhere('type', 'ex_spouse');
            });
    }

    /**
     * Get the children from this marriage
     */
    public function children()
    {
        return $this->hasMany(Relationship::class, 'marriage_id')
            ->where('type', 'child');
    }

    /**
     * Check if the marriage is divorced
     */
    public function isDivorced(): bool
    {
        return !is_null($this->divorce_date);
    }

    /**
     * Get the duration of the marriage in years
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->marriage_date) {
            return null;
        }

        $endDate = $this->divorce_date ?? now();
        return $this->marriage_date->diffInYears($endDate);
    }

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }
}