<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'company_website', 'company_image',
        'industry', 'company_size', 'company_description', 'founded_year',
        'country_id', 'contact_email', 'contact_phone', 'linkedin_url',
        'twitter_url', 'facebook_url', 'status', 'verified'
    ];

    /**
     * Define the relationship with the User model.
     * An employee belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }
}
