<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $guard;
    public function __construct(Request $request)
    {
        $this->guard = $request->route('guard');
        $this->guard = in_array($this->guard, ['admin', 'web']) ? $this->guard : 'web';
    }
    public function login(Request $request)
    {
        if (!Auth::guard($this->guard)->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['Not Found'], 404);
        }
        $user = Auth::user();

        $token = $user->createToken('auth')->plainTextToken;
        return response()->json(["data" => User::where('email', $request->email)->get(), "token" => $token], 200);
    }
    public function signup(Request $request)
    {

        if ($request->password !== $request->confPassword) {
            return response()->json(['Not matched'], 404);

        }
        try {
            DB::beginTransaction();
            $user = User::create($request->all());
            $user->sendEmailVerificationNotification();
            DB::commit();
            return response()->json(['message' => 'Registration successful! Please check your email for verification.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Registration failed. Try again later.'], 500);

        }





    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent to your email.'])
            : response()->json(['message' => 'Failed to send reset link.'], 400);
    }

    // 📌 2. Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'])
            : response()->json(['message' => 'Invalid token or email.'], 400);
    }

    public function logout(Request $request)
    {
        $request->user->token()->delete();
        Auth::guard($this->guard)->logout();
    }


}
