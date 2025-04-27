<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    use HasFactory;
    protected $fillable = [
        'employer_id',
        'category_id',
        'job_title',
        'description',
        'country_id',
        'salary',

    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function jobseeker()
    {
        return $this->belongsTo(JobSeeker::class, 'jobseeker_id');
    }
}
