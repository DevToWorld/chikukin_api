<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailGroupMember extends Model
{
    use HasFactory;

    protected $table = 'mail_group_members';
    public $timestamps = false;

    protected $fillable = [
        'group_id', 'member_id', 'created_at'
    ];

    public function group()
    {
        return $this->belongsTo(MailGroup::class, 'group_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}

