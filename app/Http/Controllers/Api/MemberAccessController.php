<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MemberAccessController extends Controller
{
    /**
     * 会員レベルの階層定義
     */
    private const MEMBERSHIP_HIERARCHY = [
        'free' => 0,
        'standard' => 1,
        'premium' => 2
    ];
    
    /**
     * コンテンツへのアクセス可否を確認
     */
    public function canAccess(Request $request, $type, $id)
    {
        $user = Auth::user();
        $contentType = strtolower($type);
        
        // コンテンツの必要レベルを取得
        $requiredLevel = $this->getContentRequiredLevel($contentType, $id);
        
        if (!$requiredLevel) {
            return response()->json([
                'success' => false,
                'message' => 'コンテンツが見つかりません'
            ], 404);
        }
        
        // アクセス可否を判定
        $canAccess = $this->checkAccess($user, $requiredLevel);
        
        // アクセスログを記録
        $this->logAccessInternal($user, $contentType, $id, $canAccess, $requiredLevel);
        
        return response()->json([
            'success' => true,
            'data' => [
                'can_access' => $canAccess,
                'required_level' => $requiredLevel,
                'user_level' => $user ? ($user->membership_type ?? 'free') : null,
                'is_authenticated' => $user !== null
            ]
        ]);
    }
    
    /**
     * アクセスログを記録
     */
    public function logAccess(Request $request)
    {
        $validated = $request->validate([
            'content_type' => 'required|string|in:publication,seminar,news,economic_report',
            'content_id' => 'required|integer',
            'access_type' => 'required|string|in:view,download,denied',
            'required_level' => 'nullable|string|in:free,standard,premium'
        ]);
        
        $user = Auth::user();
        
        // content_access_logs テーブルに記録
        DB::table('content_access_logs')->insert([
            'member_id' => $user ? $user->id : null,
            'content_type' => $validated['content_type'],
            'content_id' => $validated['content_id'],
            'access_type' => $validated['access_type'],
            'required_level' => $validated['required_level'] ?? null,
            'user_level' => $user ? ($user->membership_type ?? 'free') : null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // member_activity_logs テーブルにも記録
        if ($user) {
            DB::table('member_activity_logs')->insert([
                'member_id' => $user->id,
                'action_type' => $validated['access_type'],
                'target_type' => $validated['content_type'],
                'target_id' => $validated['content_id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'required_level' => $validated['required_level'],
                    'user_level' => $user->membership_type ?? 'free'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'アクセスログが記録されました'
        ]);
    }

    /**
     * ダウンロード履歴を取得
     */
    public function downloadHistory(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '認証が必要です'
            ], 401);
        }

        $limit = (int)($request->get('limit', 50));
        $limit = max(1, min(200, $limit));

        $logs = DB::table('content_access_logs')
            ->where('member_id', $user->id)
            ->where('access_type', 'download')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // まとめてタイトルを取得
        $pubIds = $logs->where('content_type', 'publication')->pluck('content_id')->unique()->values();
        $econIds = $logs->where('content_type', 'economic_report')->pluck('content_id')->unique()->values();
        $semiIds = $logs->where('content_type', 'seminar')->pluck('content_id')->unique()->values();

        $pubTitles = $pubIds->isEmpty() ? collect() : DB::table('publications')->whereIn('id', $pubIds)->pluck('title', 'id');
        $econTitles = $econIds->isEmpty() ? collect() : DB::table('economic_reports')->whereIn('id', $econIds)->pluck('title', 'id');
        $semiTitles = $semiIds->isEmpty() ? collect() : DB::table('seminars')->whereIn('id', $semiIds)->pluck('title', 'id');

        $items = $logs->map(function ($log) use ($pubTitles, $econTitles, $semiTitles) {
            $title = null;
            if ($log->content_type === 'publication') {
                $title = $pubTitles[$log->content_id] ?? null;
            } elseif ($log->content_type === 'economic_report') {
                $title = $econTitles[$log->content_id] ?? null;
            } elseif ($log->content_type === 'seminar') {
                $title = $semiTitles[$log->content_id] ?? null;
            }
            return [
                'id' => $log->id,
                'content_type' => $log->content_type,
                'content_id' => $log->content_id,
                'title' => $title ?? 'コンテンツ',
                'downloaded_at' => $log->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'downloads' => $items
            ]
        ]);
    }
    
    /**
     * 会員のアップグレード履歴を取得
     */
    public function getUpgradeHistory(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '認証が必要です'
            ], 401);
        }
        
        $history = DB::table('membership_upgrades')
            ->where('member_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
    
    /**
     * コンテンツの必要レベルを取得
     */
    private function getContentRequiredLevel($type, $id)
    {
        switch ($type) {
            case 'publication':
            case 'publications':
                $content = DB::table('publications')
                    ->where('id', $id)
                    ->first();
                return $content ? ($content->membership_level ?? 'free') : null;
                
            case 'seminar':
            case 'seminars':
                $content = DB::table('seminars')
                    ->where('id', $id)
                    ->first();
                return $content ? ($content->membership_requirement ?? 'free') : null;
                
            case 'news':
                $content = DB::table('news')
                    ->where('id', $id)
                    ->first();
                return $content ? ($content->membership_requirement ?? 'free') : null;
                
            default:
                return null;
        }
    }
    
    /**
     * アクセス可否をチェック
     */
    private function checkAccess($user, $requiredLevel)
    {
        // 無料コンテンツは誰でもアクセス可能
        if ($requiredLevel === 'free') {
            return true;
        }
        
        // ユーザーが未認証の場合はアクセス不可
        if (!$user) {
            return false;
        }
        
        // ユーザーの会員レベルを取得
        $userLevel = $user->membership_type ?? 'free';
        
        // 会員レベルを数値で比較
        $userLevelValue = self::MEMBERSHIP_HIERARCHY[$userLevel] ?? 0;
        $requiredLevelValue = self::MEMBERSHIP_HIERARCHY[$requiredLevel] ?? 0;
        
        return $userLevelValue >= $requiredLevelValue;
    }
    
    /**
     * アクセスログを記録（内部用）
     */
    private function logAccessInternal($user, $contentType, $contentId, $canAccess, $requiredLevel)
    {
        // テーブルが存在する場合のみログを記録
        if (Schema::hasTable('content_access_logs')) {
            DB::table('content_access_logs')->insert([
                'member_id' => $user ? $user->id : null,
                'content_type' => $contentType,
                'content_id' => $contentId,
                'access_type' => $canAccess ? 'view' : 'denied',
                'required_level' => $requiredLevel,
                'user_level' => $user ? ($user->membership_type ?? 'free') : null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
