<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EconomicStatisticsController;
use App\Http\Controllers\Api\EconomicIndicatorsController;
use App\Http\Controllers\Api\EconomicReportsController;
use App\Http\Controllers\Admin\EconomicReportManagementController;
use App\Http\Controllers\Api\FinancialReportsController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\InquiriesController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\PageContentController;
use App\Http\Controllers\Api\PublicationsController;
use App\Http\Controllers\Api\SeminarController;
use App\Http\Controllers\Api\NewsV2Controller;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\MediaReplaceController;
use App\Http\Controllers\Api\MemberAccessController;
use App\Http\Controllers\Api\DebugController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// デバッグ用シンプルエンドポイント
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now(),
        'routes_loaded' => true
    ]);
});

Route::get('/debug-routes', function () {
    $routes = [];
    foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
        if (str_starts_with($route->uri(), 'api/')) {
            $routes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
            ];
        }
    }
    return response()->json(['api_routes' => $routes]);
});

// ヘルスチェックエンドポイント
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'service' => 'chikugin-api'
    ]);
});

// Debug endpoints (enabled only with ENABLE_DEBUG_ENDPOINTS=true or local)
Route::prefix('debug')->group(function () {
    Route::get('/storage', [DebugController::class, 'storage']);
    Route::get('/rate-limits', [DebugController::class, 'rateLimits']);
});

// 認証エンドポイント
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// 後方互換性のための旧エンドポイント（非推奨）
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // auth prefix内のログアウト
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// 会員認証API（Member用）
Route::prefix('member-auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\MemberAuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\MemberAuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [App\Http\Controllers\Api\MemberAuthController::class, 'me']);
        Route::post('/logout', [App\Http\Controllers\Api\MemberAuthController::class, 'logout']);
    });
});

Route::prefix('economic-statistics')->group(function () {
    Route::get('/categories', [EconomicStatisticsController::class, 'categories']);
    Route::get('/latest', [EconomicStatisticsController::class, 'latest']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [EconomicStatisticsController::class, 'index'])->middleware('feature:economic_statistics_basic');
        Route::get('/{id}', [EconomicStatisticsController::class, 'show'])->middleware('feature:economic_statistics_basic');
    });
});

// 経済指標（公開API）
Route::prefix('economic-indicators')->group(function () {
    Route::get('/categories', [EconomicIndicatorsController::class, 'categories']);
    Route::get('/', [EconomicIndicatorsController::class, 'index']);
});

// 経済統計レポート関連のルート（パブリック）
Route::prefix('economic-reports')->name('economic-reports.')->group(function () {
    Route::get('/', [EconomicReportsController::class, 'index'])->name('index');
    Route::get('/featured', [EconomicReportsController::class, 'featured'])->name('featured');
    Route::get('/years', [EconomicReportsController::class, 'availableYears'])->name('years');
    Route::get('/categories', [EconomicReportsController::class, 'categories'])->name('categories');
    Route::get('/{id}', [EconomicReportsController::class, 'show'])->name('show');
    Route::post('/{id}/download', [EconomicReportsController::class, 'download'])->name('download');
});

Route::prefix('financial-reports')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [FinancialReportsController::class, 'index'])->middleware('membership:standard,premium');
        Route::get('/companies', [FinancialReportsController::class, 'companies'])->middleware('membership:standard,premium');
        Route::get('/latest', [FinancialReportsController::class, 'latest'])->middleware('membership:standard,premium');
        Route::get('/company/{companyName}', [FinancialReportsController::class, 'byCompany'])->middleware('membership:premium');
        Route::get('/{id}', [FinancialReportsController::class, 'show'])->middleware('membership:standard,premium');
    });
});

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    Route::get('/categories', [NewsController::class, 'categories']);
    Route::get('/featured', [NewsController::class, 'featured']);
    Route::get('/popular', [NewsController::class, 'popular']);
    Route::get('/{slug}', [NewsController::class, 'show']);
    Route::get('/{slug}/related', [NewsController::class, 'related']);
});

// お知らせ関連API（パブリック）
Route::prefix('notices')->group(function () {
    Route::get('/', [NoticeController::class, 'index']);
    Route::get('/categories', [NoticeController::class, 'categories']);
    Route::get('/{id}', [NoticeController::class, 'show']);
});

Route::prefix('services')->group(function () {
    Route::get('/', [ServicesController::class, 'index']);
    Route::get('/featured', [ServicesController::class, 'featured']);
    Route::get('/{slug}', [ServicesController::class, 'show']);
});

Route::post('/inquiries', [InquiriesController::class, 'store']);

// セミナー関連API
Route::prefix('seminars')->group(function () {
    Route::get('/', [SeminarController::class, 'index']);
    Route::get('/{id}', [SeminarController::class, 'show']);
    Route::post('/{id}/register', [SeminarController::class, 'register']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:admin-login');
    Route::post('/password/email', [AdminAuthController::class, 'sendResetLinkEmail'])->middleware('throttle:admin-login');
    Route::post('/password/reset', [AdminAuthController::class, 'resetPassword'])->middleware('throttle:admin-login');
    
    // テスト用: 認証なしエンドポイント
    Route::get('/test', function() {
        return response()->json([
            'status' => 'success',
            'message' => 'Admin API is working!',
            'timestamp' => now()
        ]);
    });
    
    Route::middleware(['auth:sanctum', 'is.admin'])->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        // MFA endpoints
        Route::post('/mfa/setup', [AdminAuthController::class, 'mfaSetup']);
        Route::post('/mfa/enable', [AdminAuthController::class, 'mfaEnable']);
        Route::post('/mfa/disable', [AdminAuthController::class, 'mfaDisable']);
        
        // ページ管理: 閲覧系は viewer まで許可、更新系は editor 以上
        Route::prefix('pages')->group(function () {
            // Read-only
            Route::middleware(['can:view-admin','throttle:admin-cms'])->group(function () {
                Route::get('/', [PageContentController::class, 'index']);
                Route::get('/media-usage', [PageContentController::class, 'mediaUsage']);
                // 管理者は公開フラグに関係なく参照可能
                Route::get('/{pageKey}', [PageContentController::class, 'adminShow']);
            });

            // Mutations
            Route::middleware('can:manage-content')->group(function () {
                Route::post('/', [PageContentController::class, 'store']);
                Route::put('/{pageKey}', [PageContentController::class, 'update']);
                Route::delete('/{pageKey}', [PageContentController::class, 'destroy']);
                Route::post('/{pageKey}/upload-image', [PageContentController::class, 'uploadImage']);
                Route::delete('/{pageKey}/delete-image', [PageContentController::class, 'deleteImage']);
                Route::post('/{pageKey}/replace-image', [PageContentController::class, 'replaceImage']);
                Route::post('/{pageKey}/replace-html-image', [PageContentController::class, 'replaceHtmlImage']);
            });
        });

        // モデル画像置換（お知らせ・刊行物・レポート・セミナーなど）
        Route::prefix('media')->middleware('can:manage-content')->group(function () {
            Route::post('/replace-model-image', [MediaReplaceController::class, 'replaceModelImage']);
            Route::post('/replace-model-html-image', [MediaReplaceController::class, 'replaceModelHtmlImage']);
        });
        
        // 刊行物（管理）: ファイルアップロード対応の管理用コントローラに切替
        Route::prefix('publications')->middleware('can:manage-content')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PublicationManagementController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Admin\PublicationManagementController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Admin\PublicationManagementController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Admin\PublicationManagementController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PublicationManagementController::class, 'destroy']);
        });

        // 経済統計レポート管理関連のルート
        Route::prefix('economic-reports')->middleware('can:manage-content')->group(function () {
            Route::get('/', [EconomicReportManagementController::class, 'index']);
            Route::get('/{id}', [EconomicReportManagementController::class, 'show']);
            Route::post('/', [EconomicReportManagementController::class, 'store']);
            Route::put('/{id}', [EconomicReportManagementController::class, 'update']);
            Route::delete('/{id}', [EconomicReportManagementController::class, 'destroy']);
            
            // 公開状態の変更
            Route::patch('/{id}/toggle-publish', [EconomicReportManagementController::class, 'togglePublishStatus']);
            Route::patch('/{id}/toggle-feature', [EconomicReportManagementController::class, 'toggleFeaturedStatus']);
            
            // 統計情報
            Route::get('/stats/overview', [EconomicReportManagementController::class, 'statistics']);
        });

        // 経済指標管理関連のルート
        Route::prefix('economic-indicators')->middleware('can:manage-content')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\EconomicIndicatorManagementController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorManagementController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Admin\EconomicIndicatorManagementController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorManagementController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorManagementController::class, 'destroy']);
        });

        // 経済指標カテゴリ管理
        Route::prefix('economic-indicator-categories')->middleware('can:manage-content')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\EconomicIndicatorCategoryManagementController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorCategoryManagementController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Admin\EconomicIndicatorCategoryManagementController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorCategoryManagementController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\EconomicIndicatorCategoryManagementController::class, 'destroy']);
        });
        
        Route::prefix('seminars')->group(function () {
            Route::get('/', [SeminarController::class, 'index']);
            Route::post('/', [SeminarController::class, 'store']);
            Route::get('/{id}', [SeminarController::class, 'show']);
            Route::put('/{id}', [SeminarController::class, 'update']);
            Route::delete('/{id}', [SeminarController::class, 'destroy']);

            // 申込承認（案B）
            Route::get('/{id}/registrations', [App\Http\Controllers\Admin\SeminarRegistrationApprovalController::class, 'index']);
            Route::post('/{id}/registrations/bulk-approve', [App\Http\Controllers\Admin\SeminarRegistrationApprovalController::class, 'bulkApprove']);
            Route::post('/{id}/registrations/{regId}/approve', [App\Http\Controllers\Admin\SeminarRegistrationApprovalController::class, 'approve']);
            Route::post('/{id}/registrations/{regId}/reject', [App\Http\Controllers\Admin\SeminarRegistrationApprovalController::class, 'reject']);
        });

        // セミナーカテゴリ管理
        Route::prefix('seminar-categories')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SeminarCategoryController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\SeminarCategoryController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Admin\SeminarCategoryController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Admin\SeminarCategoryController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\SeminarCategoryController::class, 'destroy']);
        });
        
        // 管理者用お知らせAPI
        Route::prefix('news-v2')->group(function () {
            Route::get('/', [NewsV2Controller::class, 'index']);
            Route::post('/', [NewsV2Controller::class, 'store']);
            Route::get('/{id}', [NewsV2Controller::class, 'show']);
            Route::put('/{id}', [NewsV2Controller::class, 'update']);
            Route::delete('/{id}', [NewsV2Controller::class, 'destroy']);
        });

        // CMS v2（ブロック型CMS）管理
        Route::prefix('cms-v2')->middleware(['auth:sanctum','can:manage-content','throttle:admin-cms'])->group(function () {
            Route::get('/pages', [App\Http\Controllers\Admin\CmsV2Controller::class, 'index']);
            Route::post('/pages', [App\Http\Controllers\Admin\CmsV2Controller::class, 'store']);
            Route::get('/pages/{id}', [App\Http\Controllers\Admin\CmsV2Controller::class, 'show']);
            Route::put('/pages/{id}', [App\Http\Controllers\Admin\CmsV2Controller::class, 'update']);
            Route::put('/pages/{id}/sections/{sid}', [App\Http\Controllers\Admin\CmsV2Controller::class, 'upsertSection']);
            Route::post('/pages/{id}/publish', [App\Http\Controllers\Admin\CmsV2Controller::class, 'publish'])->middleware('throttle:admin-publish');
            Route::get('/pages/{id}/versions', [App\Http\Controllers\Admin\CmsV2Controller::class, 'versions']);
            Route::post('/pages/{id}/versions/{vid}/rollback', [App\Http\Controllers\Admin\CmsV2Controller::class, 'rollback']);
            Route::post('/media', [App\Http\Controllers\Admin\CmsV2Controller::class, 'uploadMedia'])->middleware('throttle:admin-media');
            // overrides
            Route::get('/overrides', [App\Http\Controllers\Admin\CmsV2Controller::class, 'listOverrides']);
            Route::post('/overrides', [App\Http\Controllers\Admin\CmsV2Controller::class, 'setOverride'])->middleware('throttle:admin-overrides');
            // preview token
            Route::post('/pages/{id}/preview-token', [App\Http\Controllers\Admin\CmsV2Controller::class, 'issuePreviewToken'])->middleware('throttle:admin-preview');
        });

        // メールグループ管理
        Route::prefix('mail-groups')->middleware('can:manage-mails')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\MailGroupController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\MailGroupController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Admin\MailGroupController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Admin\MailGroupController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\MailGroupController::class, 'destroy']);
            Route::post('/{id}/members', [App\Http\Controllers\Admin\MailGroupController::class, 'members']);
            Route::post('/{id}/import-csv', [App\Http\Controllers\Admin\MailGroupController::class, 'importCsv']);
        });

        // メールキャンペーン管理
        Route::prefix('emails')->middleware('can:manage-mails')->group(function () {
            // static endpoints must be defined before "/{id}" to avoid conflicts
            Route::get('/templates', [App\Http\Controllers\Admin\EmailCampaignController::class, 'templates']);
            Route::delete('/templates/{id}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'deleteTemplate']);

            Route::get('/', [App\Http\Controllers\Admin\EmailCampaignController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\EmailCampaignController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'show']);
            Route::post('/{id}/preview', [App\Http\Controllers\Admin\EmailCampaignController::class, 'preview']);
            Route::post('/{id}/schedule', [App\Http\Controllers\Admin\EmailCampaignController::class, 'schedule']);
            Route::post('/{id}/send-now', [App\Http\Controllers\Admin\EmailCampaignController::class, 'sendNow']);
            Route::post('/{id}/resend-failed', [App\Http\Controllers\Admin\EmailCampaignController::class, 'resendFailed']);
            Route::post('/{id}/recipients/{recipientId}/resend', [App\Http\Controllers\Admin\EmailCampaignController::class, 'resendRecipient']);
            Route::post('/{id}/duplicate', [App\Http\Controllers\Admin\EmailCampaignController::class, 'duplicate']);
            Route::post('/{id}/mark-template', [App\Http\Controllers\Admin\EmailCampaignController::class, 'markTemplate']);
            Route::post('/{id}/unmark-template', [App\Http\Controllers\Admin\EmailCampaignController::class, 'unmarkTemplate']);
            Route::post('/{id}/create-from-template', [App\Http\Controllers\Admin\EmailCampaignController::class, 'createFromTemplate']);
            // attachments
            Route::get('/{id}/attachments', [App\Http\Controllers\Admin\EmailCampaignController::class, 'attachments']);
            Route::post('/{id}/attachments', [App\Http\Controllers\Admin\EmailCampaignController::class, 'uploadAttachment']);
            Route::delete('/{id}/attachments/{attachmentId}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'deleteAttachment']);

            // 即時送信用のシンプルエンドポイント
            Route::post('/send-simple', [App\Http\Controllers\Admin\EmailImmediateController::class, 'send']);
        });
        
        // 管理者用刊行物API
        Route::prefix('publications-v2')->group(function () {
            Route::get('/', [PublicationController::class, 'index']);
            Route::post('/', [PublicationController::class, 'store']);
            Route::get('/{id}', [PublicationController::class, 'show']);
            Route::put('/{id}', [PublicationController::class, 'update']);
            Route::delete('/{id}', [PublicationController::class, 'destroy']);
        });

        // 刊行物カテゴリ管理
        Route::prefix('publication-categories')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PublicationCategoryController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\PublicationCategoryController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Admin\PublicationCategoryController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Admin\PublicationCategoryController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\PublicationCategoryController::class, 'destroy']);
        });
        
        // 管理者用お問い合わせAPI
        Route::prefix('inquiries-v2')->group(function () {
            Route::get('/', [InquiryController::class, 'index']);
            Route::get('/{id}', [InquiryController::class, 'show']);
            Route::put('/{id}', [InquiryController::class, 'update']);
            Route::delete('/{id}', [InquiryController::class, 'destroy']);
            Route::post('/{id}/respond', [InquiryController::class, 'markAsResponded']);
        });
        
        // 会員管理API
        Route::prefix('members')->group(function () {
            Route::get('/', [MemberController::class, 'index']);
            Route::post('/', [MemberController::class, 'store']);
            Route::get('/export/csv', [MemberController::class, 'exportCsv']);
            Route::get('/stats', [MemberController::class, 'stats']);
            Route::get('/{id}', [MemberController::class, 'show']);
            Route::put('/{id}', [MemberController::class, 'update']);
            Route::delete('/{id}', [MemberController::class, 'destroy']);
            Route::patch('/{id}/status', [MemberController::class, 'updateStatus']);
            Route::patch('/{id}/membership', [MemberController::class, 'updateMembership']);
            Route::patch('/{id}/extend', [MemberController::class, 'extendMembership']);
            Route::post('/{id}/reset-password', [MemberController::class, 'resetPassword']);
        });
        
        // お知らせ管理API
        Route::prefix('notices')->middleware('can:manage-content')->group(function () {
            Route::get('/', [NoticeController::class, 'index']);
            Route::post('/', [NoticeController::class, 'store']);
            Route::get('/stats', [NoticeController::class, 'stats']);
            Route::get('/categories', [NoticeController::class, 'categories']);
            Route::get('/{id}', [NoticeController::class, 'show']);
            Route::put('/{id}', [NoticeController::class, 'update']);
            Route::delete('/{id}', [NoticeController::class, 'destroy']);
            Route::patch('/{id}/status', [NoticeController::class, 'updateStatus']);
        });

        // お知らせカテゴリ管理API
        Route::prefix('notice-categories')->middleware('can:manage-content')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\NoticeCategoryController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\NoticeCategoryController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Admin\NoticeCategoryController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Admin\NoticeCategoryController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\NoticeCategoryController::class, 'destroy']);
        });
        
        // メディア管理API
        Route::prefix('media')->middleware('can:manage-content')->group(function () {
            Route::get('/', [MediaController::class, 'index']);
            Route::post('/upload', [MediaController::class, 'upload']);
            Route::delete('/delete', [MediaController::class, 'destroy']);
            Route::put('/rename', [MediaController::class, 'update']);
            Route::get('/directories', [MediaController::class, 'directories']);
            Route::post('/directories', [MediaController::class, 'createDirectory']);
            Route::get('/stats', [MediaController::class, 'stats']);
        });

        // 管理者ユーザー管理（super_admin限定）
        Route::prefix('admin-users')->middleware('can:manage-admins')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminUserController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Admin\AdminUserController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy']);
        });
    });
});

Route::get('/pages/{pageKey}', [PageContentController::class, 'show']);

// 公開ページコンテンツAPI（認証不要）
Route::prefix('public')->middleware('throttle:public-pages')->group(function () {
    Route::get('/pages', [PageContentController::class, 'index']);
    Route::get('/pages/{pageKey}', [PageContentController::class, 'show']);
    // CMS v2 public page snapshot
    Route::get('/pages-v2/{slug}', [App\Http\Controllers\Api\CmsV2PublicController::class, 'showBySlug']);
    Route::get('/pages-v2/{slug}/preview', [App\Http\Controllers\Api\CmsV2PublicController::class, 'preview']);
    // media delivery (immutable + ETag)
    Route::get('/m/{id}/{preset}.{ext}', [App\Http\Controllers\Api\MediaV2Controller::class, 'show'])
        ->where('id', '[A-Za-z0-9\-]+')
        ->where('preset', '[A-Za-z0-9_\-]+')
        ->where('ext', '[A-Za-z0-9]+');
});

// 新しいお知らせAPI（v2）
Route::prefix('news-v2')->group(function () {
    Route::get('/', [NewsV2Controller::class, 'index']);
    Route::get('/{id}', [NewsV2Controller::class, 'show']);
});

// 新しいページAPI（v2）
Route::prefix('pages-v2')->group(function () {
    Route::get('/{slug}', [App\Http\Controllers\Api\CmsV2PublicController::class, 'showBySlug']);
});

// 新しい刊行物API
Route::prefix('publications-v2')->group(function () {
    Route::get('/', [PublicationController::class, 'index']);
    Route::get('/categories', [PublicationController::class, 'categories']);
    Route::get('/{id}', [PublicationController::class, 'show']);
    Route::get('/{id}/download', [PublicationController::class, 'download']);
});

// お問い合わせAPI
Route::prefix('inquiries-v2')->group(function () {
    Route::post('/', [InquiryController::class, 'store']); // 公開：お問い合わせ送信
});

// 会員アクセス権限API
Route::prefix('member')->middleware(['auth:sanctum', 'throttle:member'])->group(function () {
    Route::get('/can-access/{type}/{id}', [MemberAccessController::class, 'canAccess']);
    Route::post('/log-access', [MemberAccessController::class, 'logAccess']);
    Route::get('/download-history', [MemberAccessController::class, 'downloadHistory']);
    Route::get('/upgrade-history', [MemberAccessController::class, 'getUpgradeHistory']);
    
    // プロフィール管理
    Route::get('/my-profile', [App\Http\Controllers\Api\MemberProfileController::class, 'show']);
    Route::put('/my-profile', [App\Http\Controllers\Api\MemberProfileController::class, 'update']);
    Route::put('/my-profile/password', [App\Http\Controllers\Api\MemberProfileController::class, 'updatePassword']);
    Route::delete('/my-profile', [App\Http\Controllers\Api\MemberProfileController::class, 'deleteAccount']);
    
    // お気に入り機能
    Route::get('/favorites', [App\Http\Controllers\Api\MemberFavoritesController::class, 'index']);
    Route::post('/favorites/{favorite_member_id}', [App\Http\Controllers\Api\MemberFavoritesController::class, 'store']);
    Route::delete('/favorites/{favorite_member_id}', [App\Http\Controllers\Api\MemberFavoritesController::class, 'destroy']);
    Route::get('/favorites/{favorite_member_id}/check', [App\Http\Controllers\Api\MemberFavoritesController::class, 'check']);
    
    // セミナーお気に入り
    Route::get('/seminar-favorites', [App\Http\Controllers\Api\MemberSeminarFavoritesController::class, 'index']);
    Route::post('/seminar-favorites/{seminar_id}', [App\Http\Controllers\Api\MemberSeminarFavoritesController::class, 'store']);
    Route::delete('/seminar-favorites/{seminar_id}', [App\Http\Controllers\Api\MemberSeminarFavoritesController::class, 'destroy']);

    // セミナー（会員向け）
    Route::get('/seminars', [App\Http\Controllers\Api\MemberSeminarController::class, 'list']);
    Route::get('/seminar-registrations', [App\Http\Controllers\Api\MemberSeminarController::class, 'registrations']);
    
    // 会員名簿（standard以上）
    Route::get('/directory', [App\Http\Controllers\Api\MemberDirectoryController::class, 'index']);
    Route::get('/directory/{id}', [App\Http\Controllers\Api\MemberDirectoryController::class, 'show']);
    Route::get('/directory/export/csv', [App\Http\Controllers\Api\MemberDirectoryController::class, 'exportCsv']);
    Route::get('/dashboard', [App\Http\Controllers\Api\MemberDashboardController::class, 'index']);
});

// 会員マスタ（認証不要でも良いが、ここでは公開APIとして提供）
Route::prefix('member-masters')->group(function () {
    Route::get('/regions', [App\Http\Controllers\Api\MemberMasterController::class, 'regions']);
    Route::get('/industries', [App\Http\Controllers\Api\MemberMasterController::class, 'industries']);
});

Route::prefix('publications')->group(function () {
    Route::get('/', [PublicationsController::class, 'index']);
    Route::get('/featured', [PublicationsController::class, 'featured']);
    Route::get('/latest', [PublicationsController::class, 'latest']);
    Route::get('/{id}', [PublicationsController::class, 'show']);
    Route::get('/{id}/download', [PublicationsController::class, 'download'])->middleware('auth:sanctum');
});

// デバッグ用: Admin テーブル確認エンドポイント
Route::get('/debug/admins', function() {
    try {
        $admins = \App\Models\Admin::select('id', 'username', 'email', 'full_name', 'role', 'is_active')->get();
        return response()->json([
            'status' => 'success',
            'admin_count' => $admins->count(),
            'admins' => $admins,
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
});

// CORS preflight fallback for any API path
Route::options('/{any}', function () {
    return response()->noContent(204);
})->where('any', '.*');

// デバッグ用: 管理者パスワード修正エンドポイント
Route::post('/debug/fix-admin-password', function() {
    try {
        $admin = \App\Models\Admin::where('email', 'admin@chikugin-cri.co.jp')->first();
        
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found']);
        }
        
        // パスワードを直接更新（Admin モデルのミューテーターを回避）
        $admin->forceFill(['password' => \Illuminate\Support\Facades\Hash::make('admin123')])->save();
        
        // 検証
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('admin123', $admin->fresh()->password);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Admin password updated successfully',
            'password_check' => $passwordCheck,
            'admin_email' => $admin->email
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
});

// デバッグ用: 管理者自動ログイン
Route::post('/debug/admin-login', [App\Http\Controllers\Api\AdminAuthController::class, 'debugLogin']);

// デバッグ用: テーブル構造確認
Route::get('/debug/tables', function() {
    try {
        $tables = [];
        
        // Newsテーブルの存在確認と構造
        if (Schema::hasTable('news')) {
            $columns = Schema::getColumnListing('news');
            $count = \App\Models\News::count();
            $tables['news'] = [
                'exists' => true,
                'columns' => $columns,
                'count' => $count,
                'sample' => \App\Models\News::first()
            ];
        } else {
            $tables['news'] = ['exists' => false];
        }
        
        // NewsArticlesテーブルの存在確認
        if (Schema::hasTable('news_articles')) {
            $columns = Schema::getColumnListing('news_articles');
            $count = \App\Models\NewsArticle::count();
            $tables['news_articles'] = [
                'exists' => true,
                'columns' => $columns,
                'count' => $count
            ];
        } else {
            $tables['news_articles'] = ['exists' => false];
        }
        
        // Publicationsテーブルの存在確認
        if (Schema::hasTable('publications')) {
            $columns = Schema::getColumnListing('publications');
            $count = \App\Models\Publication::count();
            $tables['publications'] = [
                'exists' => true,
                'columns' => $columns,
                'count' => $count,
                'sample' => \App\Models\Publication::first()
            ];
        } else {
            $tables['publications'] = ['exists' => false];
        }
        
        return response()->json([
            'status' => 'success',
            'tables' => $tables,
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
});

// デバッグ用: AdminSeederを再実行するエンドポイント
Route::post('/debug/run-admin-seeder', function() {
    try {
        Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'AdminSeeder executed successfully.',
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// デバッグ用: ログインテストエンドポイント
Route::post('/debug/login-test', function(Request $request) {
    try {
        $admin = \App\Models\Admin::where('email', 'admin@chikugin-cri.co.jp')->first();
        
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found']);
        }
        
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('admin123', $admin->password);
        
        return response()->json([
            'status' => 'success',
            'admin_found' => true,
            'password_check' => $passwordCheck,
            'admin_email' => $admin->email,
            'is_active' => $admin->is_active
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// デバッグ用: データベース接続確認エンドポイント
Route::get('/debug/database', function() {
    try {
        $members = DB::table('members')->select('id', 'email', 'membership_type')->take(3)->get();
        $publications = DB::table('publications')->select('id', 'title', 'membership_level')->take(3)->get();
        $seminars = DB::table('seminars')->select('id', 'title', 'status', 'membership_requirement')->take(5)->get();
        
        return response()->json([
            'status' => 'success',
            'database_connection' => 'OK',
            'sample_data' => [
                'members' => $members,
                'publications' => $publications,
                'seminars' => $seminars
            ],
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()
        ], 500);
    }
});

// デバッグ用: セミナーステータス詳細確認
Route::get('/debug/seminars', function() {
    try {
        $allSeminars = DB::table('seminars')
            ->select('id', 'title', 'status', 'date', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $statusCounts = DB::table('seminars')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        return response()->json([
            'status' => 'success',
            'recent_seminars' => $allSeminars,
            'status_counts' => $statusCounts,
            'published_scope_filter' => "status IN ('scheduled', 'ongoing')",
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()
        ], 500);
    }
});
