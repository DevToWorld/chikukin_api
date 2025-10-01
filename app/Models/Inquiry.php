<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'inquiry_type',
        'status',
        'admin_notes',
        'responded_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'responded_at' => 'datetime'
    ];

    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESPONDED = 'responded';
    const STATUS_CLOSED = 'closed';

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('inquiry_type', $type);
    }

    public function markAsResponded()
    {
        $this->update([
            'status' => self::STATUS_RESPONDED,
            'responded_at' => now()
        ]);
    }
}