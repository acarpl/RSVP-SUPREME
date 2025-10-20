<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    public function login(Request $request) {
        $userData = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($userData->fails()) {
            return response()->json($userData->errors(), 422);
        }
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email dan Password salah'
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function check(Request $request) {
        if(Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $data = [
                'user' => $user,
            ];
            return response()->json($data, 200);
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }
    public function logout(Request $request) {
        $auth = auth('api');
        try {
            if ($auth->check()) {
                $auth->user()->token()->revoke();
                $auth->user()->token()->delete();
                $pesan = [
                    'success' => true,
                    'message' => 'Berhasil logout'
                ];
                $code = 200;
            } else {
                $pesan = [
                    'success' => false,
                    'message' => 'Sedang tidak login'
                ];
                $code = 401;
            }
        } catch (Exception $e) {
            $pesan = [
                'success' => false,
                'message' => 'Gagal logout'
            ];
            $code = 401;
        }
        return response()->json($pesan, $code);
    }
}