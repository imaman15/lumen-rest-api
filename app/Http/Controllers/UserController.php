<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $hashPassword = Hash::make($password);

        $user = User::create([
            'email' => $email,
            'password' => $hashPassword
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'code' => 201
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);


        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'code' => 401
            ], 401);
        }

        $isValidPassword = Hash::check($password, $user->password);
        if (!$isValidPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed. The password you entered is incorrect',
                'code' => 401
            ], 401);
        }

        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Login success',
            'code' => 200
        ], 200);
    }
}
