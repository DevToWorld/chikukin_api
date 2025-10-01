<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Support\Totp;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'otp' => 'nullable|string',
        ]);

        $admin = Admin::where('email', $request->email)
                     ->where('is_active', true)
                     ->first();

        // Account existence and lock checks
        if (!$admin) {
            // Early return with same message to avoid user enumeration
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        if ($admin->locked_until && $admin->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                'email' => [sprintf('Account locked. Try again after %s', $admin->locked_until->timezone('Asia/Tokyo')->format('Y-m-d H:i:s'))],
            ]);
        }

        if (!Hash::check($request->password, $admin->password)) {
            // Increment failed attempts and lock after threshold
            $admin->failed_attempts = ($admin->failed_attempts ?? 0) + 1;
            $maxAttempts = (int) env('ADMIN_MAX_FAILED_ATTEMPTS', 5);
            $lockMinutes = (int) env('ADMIN_LOCK_MINUTES', 15);
            if ($admin->failed_attempts >= $maxAttempts) {
                $admin->locked_until = now()->addMinutes($lockMinutes);
                $admin->failed_attempts = 0; // reset counter after lock
            }
            $admin->save();
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        // 最終ログイン時刻を更新
        $admin->update([
            'last_login_at' => now(),
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);

        // If MFA enabled, require OTP verification
        if ($admin->mfa_enabled) {
            $otp = (string) $request->input('otp', '');
            if (!$otp) {
                return response()->json([
                    'success' => false,
                    'mfa_required' => true,
                    'message' => 'One-time password (OTP) is required.',
                ], 401);
            }
            $secret = (string) $admin->mfa_secret;
            $ok = $secret && Totp::verify($secret, $otp, 1);
            if (!$ok) {
                // Do not increment password failed attempts; MFA failures are separate, but we can soft-log
                Log::warning('Admin MFA failure', ['admin_id' => $admin->id, 'email' => $admin->email, 'ip' => $request->ip()]);
                return response()->json([
                    'success' => false,
                    'mfa_required' => true,
                    'message' => 'Invalid OTP.',
                ], 401);
            }
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'user' => $admin,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * Send admin password reset link.
     */
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::broker('admins')->sendResetLink(
            ['email' => $request->email]
        );
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['success' => true, 'message' => __($status)])
            : response()->json(['success' => false, 'message' => __($status)], 422);
    }

    /**
     * Reset admin password using token from email.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin) use ($request) {
                $admin->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['success' => true, 'message' => __($status)])
            : response()->json(['success' => false, 'message' => __($status)], 422);
    }

    /**
     * Begin TOTP MFA setup: generate a new secret and return otpauth URI.
     */
    public function mfaSetup(Request $request): JsonResponse
    {
        $user = $request->user();
        // Generate 20 random bytes and base32 encode
        $random = random_bytes(20);
        $secret = \App\Support\Totp::base32Encode($random);
        $label = $user->email;
        $issuer = env('APP_NAME', 'CRI');
        $uri = Totp::provisioningUri($label, $issuer, $secret);
        // Save secret temporarily but keep disabled until verified
        $user->update(['mfa_secret' => $secret, 'mfa_enabled' => false]);
        return response()->json(['success' => true, 'secret' => $secret, 'otpauth_url' => $uri]);
    }

    /**
     * Enable TOTP after user submits a valid code from their app.
     */
    public function mfaEnable(Request $request): JsonResponse
    {
        $request->validate(['otp' => 'required|string']);
        $user = $request->user();
        $secret = (string) $user->mfa_secret;
        if (!$secret) return response()->json(['success' => false, 'message' => 'MFA not initialized'], 400);
        if (!Totp::verify($secret, $request->otp, 1)) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP'], 422);
        }
        $user->update(['mfa_enabled' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Disable TOTP (requires valid code as safeguard).
     */
    public function mfaDisable(Request $request): JsonResponse
    {
        $request->validate(['otp' => 'required|string']);
        $user = $request->user();
        $secret = (string) $user->mfa_secret;
        if (!$secret) return response()->json(['success' => false, 'message' => 'MFA not enabled'], 400);
        if (!Totp::verify($secret, $request->otp, 1)) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP'], 422);
        }
        $user->update(['mfa_enabled' => false, 'mfa_secret' => null]);
        return response()->json(['success' => true]);
    }

    /**
     * デバッグ用: 管理者でログインしてトークンを取得
     */
    public function debugLogin(Request $request): JsonResponse
    {
        if (!app()->environment('local') && !env('ENABLE_DEBUG_ADMIN_LOGIN', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Debug login is disabled in this environment.'
            ], 403);
        }
        $admin = Admin::where('email', 'admin@chikugin-cri.co.jp')
                     ->where('is_active', true)
                     ->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin account not found'
            ], 404);
        }

        // 最終ログイン時刻を更新
        $admin->update(['last_login_at' => now()]);

        $token = $admin->createToken('debug-admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $admin,
            'token' => $token,
            'message' => 'Debug admin login successful'
        ]);
    }
}
