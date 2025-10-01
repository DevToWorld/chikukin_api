<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-admins');
        $q = Admin::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function($x) use ($s){
                $x->where('username','like',"%$s%")
                  ->orWhere('email','like',"%$s%")
                  ->orWhere('full_name','like',"%$s%");
            });
        }
        return response()->json(['success'=>true,'data'=>$q->orderBy('id','desc')->paginate($request->get('per_page', 20))]);
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-admins');
        $v = Validator::make($request->all(), [
            'username' => 'required|string|max:100|unique:admins,username',
            'email' => 'required|email|max:255|unique:admins,email',
            'full_name' => 'nullable|string|max:255',
            'role' => 'required|in:super_admin,admin,editor,viewer',
            'password' => 'required|string|min:8',
            'is_active' => 'boolean'
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);
        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['success'=>true,'data'=>$admin], 201);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-admins');
        $admin = Admin::findOrFail($id);
        $v = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:100|unique:admins,username,' . $admin->id,
            'email' => 'sometimes|required|email|max:255|unique:admins,email,' . $admin->id,
            'full_name' => 'nullable|string|max:255',
            'role' => 'sometimes|required|in:super_admin,admin,editor,viewer',
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean'
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);
        $data = $request->only(['username','email','full_name','role','is_active']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $admin->update($data);
        return response()->json(['success'=>true,'data'=>$admin->fresh()]);
    }

    public function destroy($id)
    {
        Gate::authorize('manage-admins');
        $admin = Admin::findOrFail($id);
        if ($admin->id === auth()->id()) {
            return response()->json(['success'=>false,'message'=>'自分自身は削除できません'], 422);
        }
        $admin->delete();
        return response()->json(['success'=>true]);
    }
}

