<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'jobseeker_id',
        'status',
        'job_apply_date',
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
