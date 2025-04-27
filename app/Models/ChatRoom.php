<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'jobseeker_id',
        'job_id'
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
