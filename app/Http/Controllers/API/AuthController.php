<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token){
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        return response()->json(['status' => 'success', 'user' => $user, 'authorization' => ['token' => $token, 'type' => 'bearer']], 200);
    }

    public function register(Request $request){
        $validator = $request->validate([
            'name' =>'required',
            'email' =>'required||unique:users',
            'password' => 'required|min:6',
        ]);
        $user = User::create([...$validator, 'password' => Hash::make($validator['password'])
        ]);
        $token = Auth::login($user);

        return response()->json(['status' => 'success', 'user' => $user, 'authorization' => ['token' => $token, 'type' => 'bearer']], 200);
    }

    public function profile(){
        return response()->json(['status' => 'success', 'user' => Auth::user()]);
    }

    public function logout(){
        Auth::logout();
        return response()->json(['status' => 'success', 'message' => 'Successfully logged out']);
    }

    public function refresh(){
        return response()->json(['status' => 'success', 'user' => Auth::user(), 'authorization' => ['token' => Auth::refresh(), 'type' => 'bearer']]);
    }
}
