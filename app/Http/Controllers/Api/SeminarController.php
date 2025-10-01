<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeminarController extends Controller
{
    /**
     * セミナー一覧取得
     */
    public function index(Request $request)
    {
        $query = Seminar::query();

        // ステータスフィルタ（空文字は無視）
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 会員要件フィルタ
        if ($request->filled('membership_requirement')) {
            $query->where('membership_requirement', $request->membership_requirement);
        }

        // 年・月フィルタ（管理画面のUIに合わせて対応）
        if ($request->filled('year')) {
            $year = (int) $request->year;
            if ($year > 0) {
                $query->whereYear('date', $year);
            }
        }

        if ($request->filled('month')) {
            $month = (int) $request->month;
            if ($month >= 1 && $month <= 12) {
                $query->whereMonth('date', $month);
            }
        }

        // カテゴリフィルタ（存在すれば適用）
        if ($request->filled('category')) {
            $cat = (int) $request->category;
            if ($cat > 0) {
                $query->where('category_id', $cat);
            }
        }

        // 日付範囲フィルタ
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // 公開されているセミナーのみ（一般向け）
        // ただし、status=completed/cancelled が明示された場合は一般でもそのステータスを許可
        $requestedStatus = $request->input('status');
        $skipPublishedFilter = in_array($requestedStatus, ['completed', 'cancelled'], true);

        if (!$skipPublishedFilter) {
            if (!$request->user() || ($request->user() && !$request->user()->isAdmin())) {
                $query->published();
            }
        }
        // 管理者の場合は全てのステータスのセミナーを表示

        // ソート
        // 管理画面では「新規作成がすぐ見える」ことを優先し、既定で作成順降順
        $user = $request->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $sort = $request->input('sort'); // 'date_asc' | 'date_desc' | 'created_desc'

        if ($isAdmin) {
            if ($sort === 'date_asc') {
                $query->orderBy('date', 'asc')->orderBy('start_time', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('date', 'desc')->orderBy('start_time', 'desc');
            } else {
                // 既定（管理画面）：新しい作成順
                $query->orderBy('id', 'desc');
            }
        } else {
            // 公開側の既定：
            // 過去セミナーは新しい順、それ以外は従来通り昇順
            if ($requestedStatus === 'completed') {
                $query->orderBy('date', 'desc')->orderBy('start_time', 'desc');
            } else {
                $query->orderBy('date', 'asc')->orderBy('start_time', 'asc');
            }
        }

        // ページネーション
        $perPage = $request->input('per_page', 10);
        $seminars = $query->paginate($perPage);

        // 会員の種類に応じた can_register 判定（案A: 解禁日制）
        $authUser = $request->user();
        $memberType = null;
        if ($authUser && method_exists($authUser, 'membership_type')) {
            $memberType = $authUser->membership_type;
        } elseif ($authUser && property_exists($authUser, 'membership_type')) {
            $memberType = $authUser->membership_type;
        }

        $items = collect($seminars->items())->map(function ($seminar) use ($memberType) {
            $seminar->can_register_for_user = $this->canRegisterForMemberType($seminar, $memberType);
            return $seminar;
        })->all();

        return response()->json([
            'success' => true,
            'data' => [
                'seminars' => $items,
                'pagination' => [
                    'current_page' => $seminars->currentPage(),
                    'total_pages' => $seminars->lastPage(),
                    'total_items' => $seminars->total(),
                    'per_page' => $seminars->perPage(),
                ]
            ]
        ]);
    }

    /**
     * セミナー詳細取得
     */
    public function show($id)
    {
        $seminar = Seminar::with(['creator', 'registrations'])->findOrFail($id);

        // 非公開セミナーは管理者のみ
        if ($seminar->status === 'draft' && 
            (!auth()->user() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        $authUser = request()->user();
        $memberType = $authUser && property_exists($authUser, 'membership_type') ? $authUser->membership_type : null;

        return response()->json([
            'success' => true,
            'data' => [
                'seminar' => $seminar,
                'can_register' => $seminar->can_register,
                'can_register_for_user' => $this->canRegisterForMemberType($seminar, $memberType),
                'available_spots' => $seminar->available_spots,
                'formatted_date' => $seminar->formatted_date,
                'formatted_time' => $seminar->formatted_time,
                'formatted_fee' => $seminar->formatted_fee,
            ]
        ]);
    }

    /**
     * セミナー作成（管理者のみ）
     */
    public function store(Request $request)
    {
        // Fallback: handle raw JSON when Content-Type header is missing
        if (empty($request->all())) {
            $raw = json_decode($request->getContent(), true);
            if (is_array($raw)) {
                $request->merge($raw);
            }
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:200',
            'capacity' => 'nullable|integer|min:0',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,scheduled,ongoing,completed,cancelled',
            // allow legacy and new values: none/basic/free/standard/premium
            'membership_requirement' => 'nullable|in:none,basic,free,standard,premium',
            'featured_image' => 'nullable|string|max:500',
            'application_deadline' => 'nullable|date|before_or_equal:date',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => '入力データに誤りがあります',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $seminar = Seminar::create(array_merge($validator->validated(), [
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'status' => $request->input('status', 'scheduled'), // デフォルトをscheduledに変更
        ]));

        return response()->json([
            'success' => true,
            'data' => ['seminar' => $seminar],
            'message' => 'セミナーが正常に作成されました'
        ], 201);
    }

    /**
     * セミナー更新（管理者のみ）
     */
    public function update(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        // Fallback: handle raw JSON when Content-Type header is missing
        if (empty($request->all())) {
            $raw = json_decode($request->getContent(), true);
            if (is_array($raw)) {
                $request->merge($raw);
            }
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:200',
            'description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:200',
            'capacity' => 'nullable|integer|min:0',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,scheduled,ongoing,completed,cancelled',
            // allow legacy and new values: none/basic/free/standard/premium
            'membership_requirement' => 'nullable|in:none,basic,free,standard,premium',
            'featured_image' => 'nullable|string|max:500',
            'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => '入力データに誤りがあります',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $seminar->update(array_merge($validator->validated(), [
            'updated_by' => auth()->id(),
        ]));

        return response()->json([
            'success' => true,
            'data' => ['seminar' => $seminar],
            'message' => 'セミナーが正常に更新されました'
        ]);
    }

    /**
     * セミナー削除（管理者のみ）
     */
    public function destroy($id)
    {
        $seminar = Seminar::findOrFail($id);
        
        // 申込者がいる場合は削除不可
        if ($seminar->registrations()->count() > 0) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'CANNOT_DELETE',
                    'message' => '申込者がいるため削除できません'
                ]
            ], 400);
        }

        $seminar->delete();

        return response()->json([
            'success' => true,
            'message' => 'セミナーが正常に削除されました'
        ]);
    }

    /**
     * セミナー申込
     */
    public function register(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        // 申込可能性チェック
        if (!$seminar->can_register) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REGISTRATION_CLOSED',
                    'message' => 'このセミナーは申込を受け付けていません'
                ]
            ], 400);
        }

        // 会員の場合の権限チェック（Sanctum トークンから取得）
        $member = $request->user();
        // 一般公開（none/free）は誰でも申込可。必要ランクがある場合のみチェック。
        $required = $seminar->membership_requirement ?: 'none';
        if ($member && !in_array($required, ['none', 'free'], true) && !$member->canRegisterSeminar($seminar)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INSUFFICIENT_MEMBERSHIP',
                    'message' => '申込に必要な会員レベルに達していません'
                ]
            ], 403);
        }

        // 認証されたユーザーの場合、emailでmemberを検索
        if ($member) {
            // 一般公開（none/free）の場合は異なるメールでも申込を許可
            $reqKey = $seminar->membership_requirement ?: 'none';
            if (!in_array($reqKey, ['none', 'free'], true)) {
                // 認証されたユーザーのemailとフォームのemailが一致するかチェック
                if ($request->email !== $member->email) {
                    return response()->json([
                        'success' => false,
                        'error' => [
                            'code' => 'EMAIL_MISMATCH',
                            'message' => 'ログイン中のメールアドレスと入力されたメールアドレスが一致しません'
                        ]
                    ], 400);
                }
            }
        } else {
            // 認証されていない場合、emailでmemberを検索
            $member = \App\Models\Member::where('email', $request->email)->first();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:200',
            'phone' => 'nullable|string|max:20',
            'special_requests' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => '入力データに誤りがあります',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        // 重複申込チェック
        $existingRegistration = SeminarRegistration::where('seminar_id', $seminar->id)
            ->where('email', $request->email)
            ->where('attendance_status', '!=', 'cancelled')
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'ALREADY_REGISTERED',
                    'message' => '既にこのセミナーに申込済みです'
                ]
            ], 400);
        }

        // 定員チェック（再確認）
        if ($seminar->is_full) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'CAPACITY_EXCEEDED',
                    'message' => '定員に達しているため申込できません'
                ]
            ], 400);
        }

        // デバッグログを追加
        $registrationData = array_merge($validator->validated(), [
            'seminar_id' => $seminar->id,
            'member_id' => $member ? $member->id : null,
            'registration_number' => $this->generateRegistrationNumber(),
            'attendance_status' => 'registered',
            'payment_status' => $seminar->fee > 0 ? 'pending' : 'paid',
        ]);
        
        \Log::info('セミナー登録データ:', [
            'user_info' => $member ? ['id' => $member->id, 'email' => $member->email] : 'guest',
            'seminar_id' => $seminar->id,
            'registration_data' => $registrationData
        ]);
        
        $registration = SeminarRegistration::create($registrationData);

        // お問い合わせ管理に集約するため、申込情報を問い合わせとしても登録
        try {
            \App\Models\Inquiry::create([
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'company' => $registration->company,
                'subject' => 'セミナー申込: ' . ($seminar->title ?? ('ID:' . $seminar->id)),
                'message' => sprintf(
                    "申込番号: %s\nセミナーID: %d\n氏名: %s\nメール: %s\n会社: %s\n電話: %s\n要望: %s",
                    $registration->registration_number,
                    $seminar->id,
                    $registration->name,
                    $registration->email,
                    $registration->company ?? '-',
                    $registration->phone ?? '-',
                    $registration->special_requests ?? '-'
                ),
                'inquiry_type' => 'seminar',
                'status' => \App\Models\Inquiry::STATUS_NEW,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // ログに残すが、申込自体は成功として扱う
            \Log::warning('Inquiry create failed for seminar registration', [
                'error' => $e->getMessage(),
                'seminar_id' => $seminar->id,
                'registration_id' => $registration->id ?? null,
            ]);
        }

        // 参加者数更新
        $seminar->updateParticipantCount();

        return response()->json([
            'success' => true,
            'data' => [
                'registration' => $registration,
                'registration_number' => $registration->registration_number,
            ],
            'message' => 'セミナーに正常に申込されました'
        ], 201);
    }

    /**
     * 申込番号生成
     */
    private function generateRegistrationNumber()
    {
        do {
            $number = 'SEM' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (SeminarRegistration::where('registration_number', $number)->exists());

        return $number;
    }

    private function canRegisterForMemberType(Seminar $seminar, ?string $memberType): bool
    {
        // まずベース条件
        if (!$seminar->can_register) {
            return false;
        }

        // 会員要件（none は free 相当として扱う）
        $required = $seminar->membership_requirement ?: 'free';
        if ($required === 'none') $required = 'free';
        $priority = ['free' => 0, 'basic' => 1, 'standard' => 2, 'premium' => 3];

        // 未ログインは free として扱う（要件free以外は不可）
        if (!$memberType) {
            if ($required !== 'free') return false;
            $effectiveOpen = $seminar->free_open_at;
            return !$effectiveOpen || now()->gte($effectiveOpen);
        }

        // 必要ランクを満たすか
        $memberLevel = $priority[$memberType] ?? 0;
        $requiredLevel = $priority[$required] ?? 0; // default free/none
        if ($memberLevel < $requiredLevel) return false;

        // ランク別の解禁日
        switch ($memberType) {
            case 'premium':
                $effectiveOpen = $seminar->premium_open_at ?? $seminar->standard_open_at ?? $seminar->free_open_at;
                break;
            case 'standard':
            case 'basic':
                $effectiveOpen = $seminar->standard_open_at ?? $seminar->free_open_at;
                break;
            default: // free/guest
                $effectiveOpen = $seminar->free_open_at;
        }

        return !$effectiveOpen || now()->gte($effectiveOpen);
    }
}
