<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailGroup extends Model
{
    use HasFactory;

    protected $table = 'mail_groups';

    protected $fillable = [
        'name', 'description', 'created_by'
    ];

    public function members()
    {
        return $this->hasMany(MailGroupMember::class, 'group_id');
    }
}

