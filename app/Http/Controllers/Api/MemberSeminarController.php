<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberSeminarController extends Controller
{
    // 会員向け：セミナー一覧（フィルタ付き）
    public function list(Request $request)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $query = Seminar::query();
        // フィルタ: 募集中/終了/月/年度
        $status = $request->get('status'); // scheduled|completed|ongoing|cancelled
        if ($status) $query->where('status', $status);

        if ($request->filled('year')) {
            $query->whereYear('date', (int)$request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('date', (int)$request->month);
        }

        // 新しい順（要求に合わせて）
        $query->orderBy('date', 'desc')->orderBy('start_time', 'desc');

        $perPage = (int)$request->get('per_page', 10);
        $p = $query->paginate($perPage);

        $items = collect($p->items())->map(function ($s) use ($member) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'date' => $s->date,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'status' => $s->status,
                'location' => $s->location,
                'membership_requirement' => $s->membership_requirement,
                'can_register_for_user' => $member->canRegisterSeminar($s),
            ];
        });

        return response()->json(['success' => true, 'data' => ['seminars' => $items, 'pagination' => [
            'current_page' => $p->currentPage(),
            'total_pages' => $p->lastPage(),
            'total_items' => $p->total(),
            'per_page' => $p->perPage()
        ]]]);
    }

    // 自分の申込状況
    public function registrations(Request $request)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        \Log::info('申込状況取得:', [
            'member_id' => $member->id,
            'member_email' => $member->email
        ]);

        $query = SeminarRegistration::where('member_id', $member->id)
            ->join('seminars', 'seminar_registrations.seminar_id', '=', 'seminars.id')
            ->select(
                'seminar_registrations.*',
                'seminars.title','seminars.date','seminars.start_time','seminars.end_time','seminars.status as seminar_status'
            );

        // フィルタ: 月/年度/募集中(=scheduled)/終了(completed)
        if ($request->filled('year')) $query->whereYear('seminars.date', (int)$request->year);
        if ($request->filled('month')) $query->whereMonth('seminars.date', (int)$request->month);
        $status = $request->get('status');
        if ($status === 'scheduled') $query->where('seminars.status', 'scheduled');
        if ($status === 'completed') $query->where('seminars.status', 'completed');

        // 新しい順
        $query->orderBy('seminars.date', 'desc')->orderBy('seminars.start_time', 'desc');

        $perPage = (int)$request->get('per_page', 10);
        $p = $query->paginate($perPage);

        // デバッグ: 全セミナー登録レコードも確認
        $allRegistrations = SeminarRegistration::all();
        \Log::info('申込状況取得結果:', [
            'member_id' => $member->id,
            'found_registrations_count' => $p->total(),
            'all_registrations_in_db' => $allRegistrations->count(),
            'recent_registrations' => $allRegistrations->take(5)->map(function($reg) {
                return [
                    'id' => $reg->id,
                    'member_id' => $reg->member_id,
                    'email' => $reg->email,
                    'seminar_id' => $reg->seminar_id,
                    'created_at' => $reg->created_at
                ];
            })
        ]);

        return response()->json(['success' => true, 'data' => $p]);
    }
}

