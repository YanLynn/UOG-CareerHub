<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postal_code',
    ];

    /**
     * Define the relationship with the Jobseeker model.
     * A country may have many jobseekers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobseekers()
    {
        return $this->hasMany(Jobseeker::class);
    }
}
