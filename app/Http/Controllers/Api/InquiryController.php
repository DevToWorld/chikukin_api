<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Inquiry::orderBy('created_at', 'desc');

            // フィルタリング（管理者用）
            if ($request->has('status')) {
                $query->byStatus($request->status);
            }

            if ($request->has('inquiry_type')) {
                $query->byType($request->inquiry_type);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            // ページネーション
            $perPage = $request->get('per_page', 20);
            $inquiries = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'inquiries' => $inquiries->items(),
                    'pagination' => [
                        'current_page' => $inquiries->currentPage(),
                        'total_pages' => $inquiries->lastPage(),
                        'total_items' => $inquiries->total(),
                        'per_page' => $inquiries->perPage()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせの取得に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $inquiry = Inquiry::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'inquiry' => $inquiry,
                    'formatted_date' => $inquiry->created_at->format('Y.m.d H:i'),
                    'response_time' => $inquiry->responded_at 
                        ? $inquiry->created_at->diffInHours($inquiry->responded_at) . '時間' 
                        : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせが見つかりません。',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'company' => 'nullable|string|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'inquiry_type' => 'nullable|string|max:100'
            ]);

            $inquiry = Inquiry::create([
                ...$validatedData,
                'status' => Inquiry::STATUS_NEW,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // お問い合わせ受付メールの送信などの処理をここに追加可能

            return response()->json([
                'success' => true,
                'message' => 'お問い合わせを受け付けました。確認のメールをお送りしています。',
                'data' => [
                    'inquiry_id' => $inquiry->id,
                    'inquiry_number' => sprintf('INQ-%06d', $inquiry->id)
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせの送信に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $inquiry = Inquiry::findOrFail($id);

            $updateData = $request->validate([
                'status' => 'sometimes|required|in:new,in_progress,responded,closed',
                'admin_notes' => 'nullable|string',
                'inquiry_type' => 'nullable|string|max:100'
            ]);

            // ステータスが'responded'に変更された場合、responded_atを設定
            if (isset($updateData['status']) && 
                $updateData['status'] === Inquiry::STATUS_RESPONDED && 
                $inquiry->status !== Inquiry::STATUS_RESPONDED) {
                $updateData['responded_at'] = now();
            }

            $inquiry->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'お問い合わせが更新されました。',
                'data' => ['inquiry' => $inquiry]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせの更新に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            $inquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'お問い合わせが削除されました。'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせの削除に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsResponded($id): JsonResponse
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            $inquiry->markAsResponded();

            return response()->json([
                'success' => true,
                'message' => 'お問い合わせを回答済みにしました。',
                'data' => ['inquiry' => $inquiry]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ステータスの更新に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}