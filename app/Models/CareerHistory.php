<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerHistory extends Model
{
    use HasFactory;
    protected $fillable = ['job_title', 'start_date', 'end_date', 'description', 'still_in_role'];


}
