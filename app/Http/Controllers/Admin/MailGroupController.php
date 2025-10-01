<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailGroup;
use App\Models\MailGroupMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = MailGroup::query()->withCount('members');

        if ($request->filled('search')) {
            $q = $request->get('search');
            $query->where('name', 'like', "%{$q}%");
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20));

        // 既定の仮想グループを先頭に付加（全会員 / プレミアム会員 / セミナー参加者）
        try {
            $allMembers = Member::query();
            // active + 有効期限内（無料は is_active のみ）
            $allCount = Member::activeWithValidMembership()->count();

            $premiumCount = Member::where('is_active', true)
                ->where('membership_type', 'premium')
                ->where(function ($q) {
                    $q->whereNull('membership_expires_at')
                      ->orWhere('membership_expires_at', '>', now());
                })
                ->count();

            $seminarParticipants = \App\Models\SeminarRegistration::active()
                ->whereNotNull('member_id')
                ->distinct('member_id')
                ->count('member_id');

            $prepend = [
                [ 'id' => -1, 'name' => '全会員', 'description' => '全ての有効な会員', 'members_count' => $allCount ],
                [ 'id' => -2, 'name' => 'プレミアム会員', 'description' => 'プレミアム会員のみ', 'members_count' => $premiumCount ],
                [ 'id' => -3, 'name' => 'セミナー参加者', 'description' => 'これまでに申込のあった会員', 'members_count' => $seminarParticipants ],
            ];

            $arr = $groups->toArray();
            $arr['data'] = array_merge($prepend, $arr['data'] ?? []);
            return response()->json(['success' => true, 'data' => $arr]);
        } catch (\Throwable $e) {
            // フォールバック：従来のレスポンス
            return response()->json(['success' => true, 'data' => $groups]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = MailGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => optional($request->user())->id,
        ]);

        return response()->json(['success' => true, 'data' => $group], 201);
    }

    public function show($id)
    {
        $intId = (int) $id;

        // Handle virtual groups (negative IDs)
        if ($intId < 0) {
            try {
                $name = '';
                $description = '';
                $memberIds = collect();

                if ($intId === -1) {
                    // 全会員（有効な会員）
                    $name = '全会員';
                    $description = '全ての有効な会員';
                    $memberIds = \App\Models\Member::activeWithValidMembership()->pluck('id');
                } elseif ($intId === -2) {
                    // プレミアム会員
                    $name = 'プレミアム会員';
                    $description = 'プレミアム会員のみ';
                    $memberIds = \App\Models\Member::where('is_active', true)
                        ->where('membership_type', 'premium')
                        ->where(function ($q) {
                            $q->whereNull('membership_expires_at')
                              ->orWhere('membership_expires_at', '>', now());
                        })
                        ->pluck('id');
                } elseif ($intId === -3) {
                    // セミナー参加者（会員IDありの申込からユニーク抽出）
                    $name = 'セミナー参加者';
                    $description = 'これまでに申込のあった会員';
                    $memberIds = \App\Models\SeminarRegistration::active()
                        ->whereNotNull('member_id')
                        ->distinct('member_id')
                        ->pluck('member_id');
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unknown virtual mail group',
                    ], 404);
                }

                $members = $memberIds->map(function ($mid) {
                    return ['member_id' => $mid];
                })->values();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $intId,
                        'name' => $name,
                        'description' => $description,
                        'members' => $members,
                        'members_count' => $members->count(),
                        'virtual' => true,
                    ]
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load virtual group',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        $group = MailGroup::with(['members' => function ($q) {
            $q->with('member:id,company_name,representative_name,email');
        }])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $group]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = MailGroup::findOrFail($id);
        $group->update($validated);

        return response()->json(['success' => true, 'data' => $group]);
    }

    public function destroy($id)
    {
        $group = MailGroup::findOrFail($id);
        $group->delete();
        return response()->json(['success' => true]);
    }

    // Bulk add/remove members
    public function members(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:add,remove',
            'member_ids' => 'required|array',
            'member_ids.*' => 'integer|exists:members,id',
        ]);

        // 仮想グループの編集は禁止
        if ((int)$id < 0) {
            return response()->json([
                'success' => false,
                'message' => '仮想グループは編集できません。',
            ], 422);
        }

        $group = MailGroup::findOrFail($id);

        if ($validated['action'] === 'add') {
            $now = now();
            $toInsert = [];
            foreach ($validated['member_ids'] as $memberId) {
                $toInsert[] = [
                    'group_id' => $group->id,
                    'member_id' => $memberId,
                    'created_at' => $now,
                ];
            }
            // Insert ignoring duplicates
            foreach ($toInsert as $row) {
                MailGroupMember::firstOrCreate([
                    'group_id' => $row['group_id'],
                    'member_id' => $row['member_id'],
                ], ['created_at' => $row['created_at']]);
            }
        } else {
            MailGroupMember::where('group_id', $group->id)
                ->whereIn('member_id', $validated['member_ids'])
                ->delete();
        }

        $count = MailGroupMember::where('group_id', $group->id)->count();
        return response()->json(['success' => true, 'member_count' => $count]);
    }

    // CSV import: accepts file with columns: member_id or email
    public function importCsv(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // 仮想グループのCSV取り込みは禁止
        if ((int)$id < 0) {
            return response()->json([
                'success' => false,
                'message' => '仮想グループにはCSV取り込みできません。',
            ], 422);
        }

        $group = MailGroup::findOrFail($id);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) return response()->json(['success' => false, 'message' => 'ファイルを開けませんでした'], 422);

        $headers = null;
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if ($headers === null) {
                // Detect header if contains alpha
                $hasAlpha = false;
                foreach ($data as $v) { if (preg_match('/[A-Za-z]/', $v)) { $hasAlpha = true; break; } }
                if ($hasAlpha) { $headers = array_map('strtolower', $data); continue; }
                else { $headers = []; }
            }
            if ($headers) {
                $row = [];
                foreach ($headers as $i => $h) { $row[$h] = $data[$i] ?? null; }
                $rows[] = $row;
            } else {
                $rows[] = ['member_id' => $data[0] ?? null, 'email' => $data[1] ?? null];
            }
        }
        fclose($handle);

        $memberIds = [];
        $emails = [];
        foreach ($rows as $r) {
            if (!empty($r['member_id'])) $memberIds[] = (int) $r['member_id'];
            elseif (!empty($r['email'])) $emails[] = trim($r['email']);
        }
        $memberIds = array_filter(array_unique($memberIds));
        $emails = array_filter(array_unique($emails));

        $mappedIds = [];
        if (!empty($emails)) {
            // email は暗号化保存のため、照合は email_index（小文字トリム）で行う
            $normalized = array_map(function ($e) { return mb_strtolower(trim($e)); }, $emails);
            $mappedIds = Member::whereIn('email_index', $normalized)->pluck('id')->all();
        }
        $allIds = array_values(array_unique(array_merge($memberIds, $mappedIds)));

        $inserted = 0;
        foreach ($allIds as $mid) {
            $m = MailGroupMember::firstOrCreate(['group_id' => $group->id, 'member_id' => $mid], ['created_at' => now()]);
            if ($m->wasRecentlyCreated) $inserted++;
        }

        return response()->json(['success' => true, 'inserted' => $inserted, 'total_ids' => count($allIds)]);
    }
}
