<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeminarFavorite;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberSeminarFavoritesController extends Controller
{
    public function index(Request $request)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $query = SeminarFavorite::where('member_id', $member->id)
            ->join('seminars', 'seminar_favorites.seminar_id', '=', 'seminars.id')
            ->select('seminars.*', 'seminar_favorites.created_at as favorited_at')
            ->orderBy('seminar_favorites.created_at', 'desc');

        if (!$request->boolean('include_completed')) {
            $query->where('seminars.status', '!=', 'completed');
        }

        $items = $query->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store($seminarId)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        $seminar = Seminar::find($seminarId);
        if (!$seminar) return response()->json(['success' => false, 'message' => 'Seminar not found'], 404);

        $fav = SeminarFavorite::firstOrCreate([
            'member_id' => $member->id,
            'seminar_id' => $seminarId,
        ], ['created_at' => now()]);

        return response()->json(['success' => true, 'data' => $fav]);
    }

    public function destroy($seminarId)
    {
        $member = Auth::guard('sanctum')->user();
        if (!$member) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        $deleted = SeminarFavorite::where('member_id', $member->id)->where('seminar_id', $seminarId)->delete();
        return response()->json(['success' => $deleted > 0]);
    }
}

