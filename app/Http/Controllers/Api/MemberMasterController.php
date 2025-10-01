<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Industry;

class MemberMasterController extends Controller
{
    public function regions()
    {
        $items = Region::orderBy('sort_order')->orderBy('id')->get(['code','name']);
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function industries()
    {
        $items = Industry::orderBy('sort_order')->orderBy('id')->get(['code','name']);
        return response()->json(['success' => true, 'data' => $items]);
    }
}

