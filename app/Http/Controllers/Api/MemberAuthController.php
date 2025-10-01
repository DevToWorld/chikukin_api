<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    /**
     * 会員登録
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'representative_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email_index',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ], [
            'company_name.required' => '会社名は必須です',
            'representative_name.required' => '代表者名は必須です',
            'email.required' => 'メールアドレスは必須です',
            'email.email' => 'メールアドレスの形式が正しくありません',
            'email.unique' => 'このメールアドレスは既に登録されています',
            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワード確認が一致しません',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '入力内容にエラーがあります',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $member = Member::create([
                'company_name' => $request->company_name,
                'representative_name' => $request->representative_name,
                'email' => $request->email,
                'email_index' => mb_strtolower(trim($request->email)),
                'password' => $request->password, // Memberモデルでハッシュ化
                'phone' => $request->phone,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'membership_type' => 'free', // デフォルトは無料会員
                'status' => 'pending', // デフォルトは承認待ち
                'is_active' => true,
                'joined_date' => now(),
            ]);

            $token = $member->createToken('member_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => '会員登録が完了しました',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'member' => [
                    'id' => $member->id,
                    'company_name' => $member->company_name,
                    'representative_name' => $member->representative_name,
                    'email' => $member->email,
                    'membership_type' => $member->membership_type,
                    'status' => $member->status,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '会員登録に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員ログイン
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'メールアドレスは必須です',
            'email.email' => 'メールアドレスの形式が正しくありません',
            'password.required' => 'パスワードは必須です',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '入力内容にエラーがあります',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $member = Member::where('email_index', mb_strtolower(trim($request->email)))->first();

            if (!$member || !Hash::check($request->password, $member->password)) {
                return response()->json([
                    'success' => false,
                    'message' => '認証情報が正しくありません。',
                    'errors' => [
                        'email' => ['メールアドレスまたはパスワードが正しくありません。']
                    ]
                ], 422);
            }

            // アカウントが無効の場合
            if (!$member->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'アカウントが無効です。管理者にお問い合わせください。',
                    'errors' => [
                        'account' => ['アカウントが無効です。']
                    ]
                ], 422);
            }

            // ステータスチェック
            if ($member->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'このアカウントは解約されています。',
                    'errors' => [
                        'account' => ['アカウントが解約されています。']
                    ]
                ], 422);
            }

            // 最終ログイン時刻を更新
            $member->update(['last_login_at' => now()]);

            $token = $member->createToken('member_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'ログインしました',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'member' => [
                    'id' => $member->id,
                    'company_name' => $member->company_name,
                    'representative_name' => $member->representative_name,
                    'email' => $member->email,
                    'membership_type' => $member->membership_type,
                    'status' => $member->status,
                    'membership_expires_at' => $member->membership_expires_at,
                    'is_active' => $member->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ログインに失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員ログアウト
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'ログアウトしました。'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ログアウトに失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員情報取得
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $member = $request->user();
            
            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'company_name' => $member->company_name,
                    'representative_name' => $member->representative_name,
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'postal_code' => $member->postal_code,
                    'address' => $member->address,
                    'membership_type' => $member->membership_type,
                    'status' => $member->status,
                    'membership_expires_at' => $member->membership_expires_at,
                    'is_active' => $member->is_active,
                    'created_at' => $member->created_at,
                    'updated_at' => $member->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '会員情報の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
