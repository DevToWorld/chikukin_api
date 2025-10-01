<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id',
        'member_id',
        'name',
        'email',
        'company',
        'phone',
        'special_requests',
        'attendance_status',
        'payment_status',
        'registration_number',
        'approval_status',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejection_reason',
    ];

    // リレーションシップ
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('attendance_status', '!=', 'cancelled');
    }

    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // 承認系スコープ
    public function scopeApprovalPending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    // アクセサー
    public function getStatusColorAttribute()
    {
        $colors = [
            'registered' => 'blue',
            'attended' => 'green',
            'cancelled' => 'red',
            'no_show' => 'orange',
        ];

        return $colors[$this->attendance_status] ?? 'gray';
    }

    public function getPaymentStatusColorAttribute()
    {
        $colors = [
            'pending' => 'orange',
            'paid' => 'green',
            'refunded' => 'red',
        ];

        return $colors[$this->payment_status] ?? 'gray';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'registered' => '申込済み',
            'attended' => '参加済み',
            'cancelled' => 'キャンセル',
            'no_show' => '欠席',
        ];

        return $statuses[$this->attendance_status] ?? '不明';
    }

    public function getPaymentStatusTextAttribute()
    {
        $statuses = [
            'pending' => '支払い待ち',
            'paid' => '支払い済み',
            'refunded' => '返金済み',
        ];

        return $statuses[$this->payment_status] ?? '不明';
    }

    // ヘルパーメソッド
    public function isActive()
    {
        return $this->attendance_status !== 'cancelled';
    }

    public function isAttended()
    {
        return $this->attendance_status === 'attended';
    }

    public function isCancelled()
    {
        return $this->attendance_status === 'cancelled';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function cancel()
    {
        $this->update(['attendance_status' => 'cancelled']);
        
        // セミナーの参加者数を更新
        $this->seminar->updateParticipantCount();
    }

    public function markAsAttended()
    {
        $this->update(['attendance_status' => 'attended']);
    }

    public function markAsPaid()
    {
        $this->update(['payment_status' => 'paid']);
    }
}
