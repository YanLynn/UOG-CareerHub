<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;


    protected $table = 'jobseekers';
    protected $fillable = [
        'user_id',
        'profile_img',
        'social_url',
        'personal_summary',
        'resume_url',
        'resume_file_name',
        'country_id',
        'education_id',
        'skill_id',
        'language_id',
        'career_history_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function educations()
    {
        if (empty($this->education_id)) {
            return collect([]);
        }
        $educationIds = array_map('intval', array_filter(explode(',', $this->education_id)));
        return Education::whereIn('id', $educationIds)->get();
    }
    public function skills()
    {
        if (empty($this->skill_id)) {
            return collect([]);
        }
        $skillIds = array_map('intval', array_filter(explode(',', $this->skill_id)));
        return Skill::whereIn('id', $skillIds)->get();
    }

    public function languages()
    {
        if (empty($this->language_id)) {
            return collect([]);
        }
        $languageIds = array_map('intval', array_filter(explode(',', $this->language_id)));
        return Language::whereIn('id', $languageIds)->get();
    }

    public function careerHistories()
    {
        if (empty($this->career_history_id)) {
            return collect([]);
        }
        $careerHistoryIds = array_map('intval', array_filter(explode(',', $this->career_history_id)));
        return CareerHistory::whereIn('id', $careerHistoryIds)->get();
    }
}
