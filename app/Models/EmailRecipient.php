<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'email', 'member_id', 'status', 'sent_at', 'error'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}

