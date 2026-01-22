<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPrefixedId;

class Enrollment extends Model
{
    use HasFactory, HasPrefixedId;

    protected $prefix = 'enroll';

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at'
    ];
}
