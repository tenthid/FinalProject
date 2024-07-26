<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required | confirmed | min:6'
        ],
        [ 
            'name.required' => 'wajib ada',
            'email.required' => 'wajib ada',
            'email.email' => 'email harus berformat email',
            'password.required' => 'wajib ada',
            'password.confirmed' => 'password konfirmasi tidak sama',
            'password.min' => 'password minimal 6 karakter',
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors' => $validateData->errors()], 422);
        } 

        $isEmailExist = User::where('email', $request->email)->exists();
        
        if(!$isEmailExist) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json(['message' => 'Register berhasil'], 201);
        }

        return response()->json(['message' => "Email $request->email sudah dipakai, register menggunakan email lain!"]);
    }

    public function login (Request $request) {
        $validateData = Validator::make($request->all(),[
            'email' => 'required | email',
            'password' => 'required',
            // 'remember' => 'required|boolean'
        ],
        [ 
            'email.required' => 'wajib ada',
            'email.email' => 'email harus berformat email',
            'password.required' => 'wajib ada',
            // 'remember.required' => 'wajib ada',
            // 'remember.boolean' => 'harus bernilai 1 atau 0',
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors' => $validateData->errors()], 422);
        }
        
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'message' => "User dengan email $request->email tidak ditemukan"
            ], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken("tokenName")->plainTextToken;
        
        return response()->json(['data' => [
            'token' => $token
        ]]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        echo "test";

        return response()->json(['message' => 'logout berhasil'], 200);
    }

    public function forgotPassword(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'email harus ada',
            'email.email' => 'email harus sesuai format',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [
                    "errors" => $validator->errors()
                ]
            ], 422);    
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ],
        [
            'email.required' => 'email harus ada',
            'email.email' => 'email harus sesuai format',
            'password.required' => 'password harus ada',
            'password.min' => 'password harus terdiri dari 6 karakter',
            'password.confirmed' => 'password konfirmasi harus sesuai dengan passwordr',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [
                    "errors" => $validator->errors()
                ]
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }
}
