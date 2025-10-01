<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberFavorite extends Model
{
    use HasFactory;

    protected $table = 'member_favorites';
    public $timestamps = false;
    public $incrementing = false; // composite PK; avoid insertGetId

    protected $fillable = [
        'member_id',
        'favorite_member_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // リレーション
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function favoriteMember()
    {
        return $this->belongsTo(Member::class, 'favorite_member_id');
    }

    // スコープ
    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeByFavoriteMember($query, $favoriteMemberId)
    {
        return $query->where('favorite_member_id', $favoriteMemberId);
    }
}
