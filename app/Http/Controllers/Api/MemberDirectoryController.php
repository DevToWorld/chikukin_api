<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberDirectoryController extends Controller
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
     * 会員名簿一覧を取得（standard以上のみアクセス可能）
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

            // standard以上の会員のみアクセス可能
            if (!in_array($member->membership_type, ['standard', 'premium'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'この機能はスタンダード会員以上でご利用いただけます'
                ], 403);
            }

            $query = Member::query()
                ->where('status', 'active')
                ->where('is_active', true)
                ->select([
                    'id', 'company_name', 'representative_name', 
                    'email', 'phone', 'address', 'membership_type',
                    'created_at'
                ]);

            // 検索フィルター
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'LIKE', "%{$search}%")
                      ->orWhere('representative_name', 'LIKE', "%{$search}%");
                    // 住所は暗号化保存のためDB側のLIKE検索対象にしない
                });
            }

            // 会社名フィルター
            if ($request->filled('company')) {
                $query->where('company_name', 'LIKE', "%{$request->company}%");
            }

            // 会員種別フィルター
            if ($request->filled('membership_type')) {
                $query->where('membership_type', $request->membership_type);
            }

            // 地域フィルター（regionカラムがあれば優先）
            if ($request->filled('region')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'region')) {
                    $query->where('region', $request->region);
                }
                // 住所は暗号化保存のためfallbackのLIKE検索は実施しない
            }

            // 業種フィルター
            if ($request->filled('industry')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'industry')) {
                    $query->where('industry', $request->industry);
                }
            }

            // お気に入りのみ（現在のユーザーが登録したお気に入りに限定）
            if ($request->boolean('favorites_only')) {
                $favoriteIds = MemberFavorite::where('member_id', $member->id)
                    ->pluck('favorite_member_id');
                if ($favoriteIds->isNotEmpty()) {
                    $query->whereIn('id', $favoriteIds);
                } else {
                    $query->whereRaw('1=0');
                }
            }

            // ソート
            $sortBy = $request->get('sort', 'company_name');
            $sortOrder = $request->get('order', 'asc');
            
            if (in_array($sortBy, ['company_name', 'representative_name', 'membership_type', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // ページネーション
            $perPage = min($request->get('per_page', 20), 100); // 最大100件
            $members = $query->paginate($perPage);

            // 現在のユーザーのお気に入り一覧を取得
            $favoriteIds = MemberFavorite::where('member_id', $member->id)
                ->pluck('favorite_member_id')
                ->toArray();

            // レスポンスデータの整形
            $data = $members->getCollection()->map(function ($item) use ($favoriteIds) {
                return [
                    'id' => $item->id,
                    'company_name' => $item->company_name,
                    'representative_name' => $this->maybeDecrypt($item->representative_name),
                    'email' => $this->maybeDecrypt($item->email),
                    'phone' => $this->maybeDecrypt($item->phone),
                    'address' => $this->maybeDecrypt($item->address),
                    'membership_type' => $item->membership_type,
                    'joined_at' => $item->created_at,
                    'is_favorite' => in_array($item->id, $favoriteIds),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'current_page' => $members->currentPage(),
                'last_page' => $members->lastPage(),
                'per_page' => $members->perPage(),
                'total' => $members->total(),
                'from' => $members->firstItem(),
                'to' => $members->lastItem(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '会員名簿の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員詳細情報を取得
     */
    public function show(Request $request, $id)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            // standard以上の会員のみアクセス可能
            if (!in_array($member->membership_type, ['standard', 'premium'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'この機能はスタンダード会員以上でご利用いただけます'
                ], 403);
            }

            $targetMember = Member::where('id', $id)
                ->where('status', 'active')
                ->where('is_active', true)
                ->select([
                    'id', 'company_name', 'representative_name', 
                    'email', 'phone', 'address', 'membership_type',
                    'created_at'
                ])
                ->first();

            if (!$targetMember) {
                return response()->json([
                    'success' => false,
                    'message' => '会員が見つかりません'
                ], 404);
            }

            // お気に入り状態をチェック
            $isFavorite = MemberFavorite::where('member_id', $member->id)
                ->where('favorite_member_id', $id)
                ->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $targetMember->id,
                    'company_name' => $targetMember->company_name,
                    'representative_name' => $this->maybeDecrypt($targetMember->representative_name),
                    'email' => $this->maybeDecrypt($targetMember->email),
                    'phone' => $this->maybeDecrypt($targetMember->phone),
                    'address' => $this->maybeDecrypt($targetMember->address),
                    'membership_type' => $targetMember->membership_type,
                    'joined_at' => $targetMember->created_at,
                    'is_favorite' => $isFavorite,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '会員詳細の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 会員名簿のCSVエクスポート
     */
    public function exportCsv(Request $request)
    {
        try {
            $member = Auth::guard('sanctum')->user();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => '認証が必要です'
                ], 401);
            }

            // スタンダード以上はCSVエクスポート可能
            if (!in_array($member->membership_type, ['standard', 'premium'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'この機能はスタンダード会員以上でご利用いただけます'
                ], 403);
            }

            $query = Member::query()
                ->where('status', 'active')
                ->where('is_active', true)
                ->select([
                    'company_name', 'representative_name', 
                    'email', 'phone', 'address', 'membership_type',
                    'created_at'
                ]);

            // 同じフィルターを適用
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'LIKE', "%{$search}%")
                      ->orWhere('representative_name', 'LIKE', "%{$search}%");
                    // 住所は暗号化保存のためDB側のLIKE検索対象にしない
                });
            }

            if ($request->filled('company')) {
                $query->where('company_name', 'LIKE', "%{$request->company}%");
            }

            if ($request->filled('membership_type')) {
                $query->where('membership_type', $request->membership_type);
            }

            if ($request->filled('region')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'region')) {
                    $query->where('region', $request->region);
                }
                // 住所は暗号化保存のためfallbackのLIKE検索は実施しない
            }

            if ($request->filled('industry')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('members', 'industry')) {
                    $query->where('industry', $request->industry);
                }
            }

            if ($request->boolean('favorites_only')) {
                $favoriteIds = MemberFavorite::where('member_id', $member->id)
                    ->pluck('favorite_member_id');
                if ($favoriteIds->isNotEmpty()) {
                    $query->whereIn('id', $favoriteIds);
                } else {
                    $query->whereRaw('1=0');
                }
            }

            $members = $query->orderBy('company_name')->get();

            // CSVヘッダー
            $csvData = "会社名,代表者名,メールアドレス,電話番号,住所,会員種別,入会日\n";

            // CSVデータ
            foreach ($members as $member) {
                $csvData .= implode(',', [
                    '"' . str_replace('"', '""', $member->company_name) . '"',
                    '"' . str_replace('"', '""', $this->maybeDecrypt($member->representative_name)) . '"',
                    '"' . str_replace('"', '""', $this->maybeDecrypt($member->email)) . '"',
                    '"' . str_replace('"', '""', $this->maybeDecrypt($member->phone)) . '"',
                    '"' . str_replace('"', '""', $this->maybeDecrypt($member->address)) . '"',
                    '"' . str_replace('"', '""', $member->membership_type) . '"',
                    '"' . $member->created_at->format('Y-m-d') . '"',
                ]) . "\n";
            }

            $filename = '会員名簿_' . date('Y-m-d_H-i-s') . '.csv';

            // Excel互換のためUTF-8 BOMを付与
            $bom = "\xEF\xBB\xBF";

            return response($bom . $csvData, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'CSVエクスポートに失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
