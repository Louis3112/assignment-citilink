<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;

class CourseController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    /**
     * GET /api/courses
    */
    public function index(){
        $courses = Course::with('tutor:id,name,email')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of courses retrieved',
            'data' => $courses
        ]);
    }

    /**
     * POST /api/courses
    */
    public function store(Request $request){
        $user = Auth::user();

        if ($user->role !== 'tutor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only tutors can create courses.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => $user->id 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    /**
     * GET /api/courses/{id}
    */
    public function show($id){
        $course = Course::with('tutor:id,name,email')->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * PUT /api/courses/{id}
    */
    public function update(Request $request, $id){
        $course = Course::find($id);

        if ($course->created_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. You are not the owner of this course.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255', 
            'description' => 'required|string',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $course->update($request->only(['title', 'description']));

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    /**
     * DELETE /api/courses/{id}
     */
    public function destroy($id){
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['status' => 'error', 'message' => 'Course not found'], 404);
        }

        if ($course->created_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. You are not the owner of this course.'
            ], 403);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }

    public function showStudents($id){
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['status' => 'error', 'message' => 'Course not found'], 404);
        }

        if ($course->created_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. You are not the owner of this course.'
            ], 403);
        }

        $students = $course->students()
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of enrolled students retrieved',
            'data' => $students
        ]);
    }
}
