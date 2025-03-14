<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable = [
        'employer_id',
        'category_id',
        'job_title',
        'description',
        'country_id',
        'salary',
        'job_type',
        'job_location',
        'experience_level',
        'requirements',
        'employment_status',
        'application_deadline',
        'benefits',
        'visibility'
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * Relationship: Job belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Job belongs to a Country
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
