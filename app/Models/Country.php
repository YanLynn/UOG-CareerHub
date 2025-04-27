<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'code',
        'country',
        'countryCode',
        'flag'
    ];

    /**
     * Define the relationship with the Jobseeker model.
     * A country may have many jobseekers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function jobseekers()
    // {
    //     return $this->hasMany(Jobseeker::class);
    // }
}
