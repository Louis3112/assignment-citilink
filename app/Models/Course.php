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
        'description',
        'created_by'
    ];

    public function tutor(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withTimestamps();
    }

    public function students(){
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('enrolled_at')
                    ->withTimestamps();
    }
}
