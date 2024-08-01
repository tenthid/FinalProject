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
use App\Models\PasswordResetToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | confirmed | min:6'
        ],
        [ 
            'name.required' => 'wajib ada',
            'email.required' => 'wajib ada',
            'email.unique' => "email $request->email sudah ada, gunakan email lain",
            'email.email' => 'email harus berformat email',
            'password.required' => 'wajib ada',
            'password.confirmed' => 'password konfirmasi tidak sama',
            'password.min' => 'password minimal 6 karakter',
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors' => $validateData->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        User::query();

        return response()->json(['message' => 'Register berhasil'], 201);
    }

    public function login (Request $request) {
        $validateData = Validator::make($request->all(),[
            'email' => 'required | email',
            'password' => 'required',
        ],
        [ 
            'email.required' => 'wajib ada',
            'email.email' => 'email harus berformat email',
            'password.required' => 'wajib ada',
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
        
        echo "helo there";

        return response()->json(['data' => [
            'token' => $token
        ]]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

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

    public function verifyToken(Request $request) {
        $validateData = Validator::make($request->all(), [
            'token' => 'required'
        ],[
            'token.required' => 'token wajib diisi'
        ]);

        $token = $request->input('token');

        $resetToken = PasswordResetToken::where('token', $token)->get();

        if(count($resetToken) === 0) {
            return response()->json(['message' => 'token tidak valid/ tidak ada', 'redirect' => false]);
        }

        return response()->json(['message' => 'token valid', 'redirect' => true]);
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
