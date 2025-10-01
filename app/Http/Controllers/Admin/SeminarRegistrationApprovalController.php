<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\Request;

class SeminarRegistrationApprovalController extends Controller
{
    public function index(Request $request, $seminarId)
    {
        $seminar = Seminar::findOrFail($seminarId);
        $query = SeminarRegistration::where('seminar_id', $seminar->id)
            ->select('id','seminar_id','member_id','name','email','company','phone','attendance_status','payment_status','approval_status','approved_at','rejected_at','rejection_reason','registration_number','created_at');

        if ($request->filled('status')) {
            $status = $request->get('status');
            if (in_array($status, ['pending','approved','rejected'])) {
                $query->where('approval_status', $status);
            }
        }

        $perPage = (int) $request->get('per_page', 20);
        $items = $query->orderBy('id','desc')->paginate($perPage);

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function approve($seminarId, $regId)
    {
        $registration = SeminarRegistration::where('seminar_id', $seminarId)->findOrFail($regId);
        $registration->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);

        return response()->json(['success' => true, 'data' => $registration]);
    }

    public function reject(Request $request, $seminarId, $regId)
    {
        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $registration = SeminarRegistration::where('seminar_id', $seminarId)->findOrFail($regId);
        $registration->update([
            'approval_status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => $request->input('reason'),
        ]);

        return response()->json(['success' => true, 'data' => $registration]);
    }

    public function bulkApprove(Request $request, $seminarId)
    {
        $data = $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'integer'
        ]);

        SeminarRegistration::where('seminar_id', $seminarId)
            ->whereIn('id', $data['registration_ids'])
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);

        return response()->json(['success' => true]);
    }
}

