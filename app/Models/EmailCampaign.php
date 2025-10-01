<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'body_html', 'body_text', 'status', 'scheduled_at', 'created_by', 'is_template'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function recipients()
    {
        return $this->hasMany(EmailRecipient::class, 'campaign_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(EmailAttachment::class, 'campaign_id');
    }
}
