<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * POST /api/auth/register
    */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,tutor',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        $token = auth()->guard('api')->login($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    /**
     * POST /api/auth/login
    */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->guard('api')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',    
                'message' => 'Unauthorized'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * GET /api/auth/me
    */
    public function me(){
        return response()->json(auth()->guard('api')->user());
    }

    /**
     * POST /api/auth/logout
    */
    public function logout(){
        auth()->guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * DELETE /api/auth/delete-account
    */
    public function deleteUser(){
        $user = auth()->guard('api')->user();
        $user->enrolledCourses()->detach();
        \App\Models\Course::where('created_by', $user->id)->delete();

        auth()->guard('api')->logout();

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User and all related data has been deleted successfully'
        ]);
    }

    /**
     * Response token
    */
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => auth()->guard('api')->user()
        ]);
    }
}
