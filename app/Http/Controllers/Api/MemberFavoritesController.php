<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MemberFavoritesController extends Controller
{
    private function maybeDecrypt($value)
    {
        if (!is_string($value) || $value === '') return $value;
        $decoded = base64_decode($value, true);
        if ($decoded === false) return $value;
        $json = json_decode($decoded, true);
        if (!is_array($json) || !isset($json['iv'], $json['value'], $json['mac'])) return $value;
        $out = $value;
        for ($i = 0; $i < 2; $i++) {
            $decoded = base64_decode($out, true);
            $json = $decoded !== false ? json_decode($decoded, true) : null;
            if (!is_array($json) || !isset($json['iv'], $json['value'], $json['mac'])) break;
            try { $out = \Illuminate\Support\Facades\Crypt::decryptString($out); }
            catch (\Throwable $e) { return $value; }
        }
        return $out;
    }
    /**
     * お気に入り会員一覧を取得
     */
    public function index(Request $request)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            $favorites = $member->favoriteMembers()
                ->select(['members.id', 'company_name', 'representative_name', 'email', 'phone', 'address', 'membership_type'])
                ->orderBy('member_favorites.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $favorites->map(function ($favorite) {
                    return [
                        'id' => $favorite->id,
                        'company_name' => $favorite->company_name,
                        'representative_name' => $this->maybeDecrypt($favorite->representative_name),
                        'email' => $this->maybeDecrypt($favorite->email),
                        'phone' => $this->maybeDecrypt($favorite->phone),
                        'address' => $this->maybeDecrypt($favorite->address),
                        'membership_type' => $favorite->membership_type,
                        'favorited_at' => $favorite->pivot->created_at,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お気に入り一覧の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員をお気に入りに追加
     */
    public function store(Request $request, $favoriteMemberId)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            // 自分自身をお気に入りに追加することはできない
            if ($member->id == $favoriteMemberId) {
                return response()->json([
                    'success' => false,
                    'message' => '自分自身をお気に入りに追加することはできません'
                ], 400);
            }

            // 対象の会員が存在するかチェック
            $targetMember = Member::find($favoriteMemberId);
            if (!$targetMember) {
                return response()->json([
                    'success' => false,
                    'message' => '指定された会員が見つかりません'
                ], 404);
            }

            // 既に登録済みでもOKとする（冪等化）
            $favorite = MemberFavorite::firstOrCreate([
                'member_id' => $member->id,
                'favorite_member_id' => $favoriteMemberId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'お気に入りに追加しました',
                'data' => [
                    'member_id' => $favorite->member_id,
                    'favorite_member_id' => $favorite->favorite_member_id,
                    'created_at' => $favorite->created_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お気に入りの追加に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員をお気に入りから削除
     */
    public function destroy(Request $request, $favoriteMemberId)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            // お気に入りから削除
            $deleted = MemberFavorite::where('member_id', $member->id)
                ->where('favorite_member_id', $favoriteMemberId)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'お気に入りから削除しました'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'お気に入りが見つかりません'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お気に入りの削除に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 指定した会員がお気に入りに登録されているかチェック
     */
    public function check(Request $request, $favoriteMemberId)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            $isFavorite = MemberFavorite::where('member_id', $member->id)
                ->where('favorite_member_id', $favoriteMemberId)
                ->exists();

            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お気に入り状態の確認に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
