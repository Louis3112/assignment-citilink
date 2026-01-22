<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    /**
     * POST /api/courses/{id}/enroll
    */
    public function store($id){
        $user = Auth::user();
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['status' => 'error', 'message' => 'Course not found'], 404);
        }

        if ($user->enrolledCourses()->where('course_id', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are already enrolled in this course'
            ], 409); 
        }

        if ($course->created_by == $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot enroll in your own course'
            ], 422);
        }
        
        $enrollment = Enrollment::create([
            'course_id' => $id,
            'user_id'   => $user->id,
            'enrolled_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Enrolled successfully',
            'data' => [
                'enrollment_id' => $enrollment->id, 
                'course' => $course->only(['id', 'title']),
                'enrolled_at' => $enrollment->enrolled_at
            ]
        ], 201);
    }

    /**
     * GET /api/my-courses
    */
    public function index(){
        $user = Auth::user();

        $courses = $user->enrolledCourses()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'My enrolled courses retrieved',
            'data' => $courses
        ]);
    }
}
