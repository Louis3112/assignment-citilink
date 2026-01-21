<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPrefixedId;

class Course extends Model
{
    use HasFactory, HasPrefixedId;

    protected $prefix = 'course';
    
    protected $fillable = [
        'title', 
        'description'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withTimestamps();
    }
}
