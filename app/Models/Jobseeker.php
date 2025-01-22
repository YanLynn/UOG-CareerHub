<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobseeker extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'language_id',
        'skill_id',
        'country_id',
        'profile_img',
        'social_url',
        'personal_summary',
        'career_history_id',
        'education_id',
        'resume_url',
    ];

    /**
     * Define the relationship with the User model.
     * A jobseeker belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the Country model.
     * A jobseeker belongs to a country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
