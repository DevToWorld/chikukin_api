<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarFavorite extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'seminar_favorites';
    protected $fillable = ['member_id','seminar_id','created_at'];

    public function member() { return $this->belongsTo(Member::class, 'member_id'); }
    public function seminar() { return $this->belongsTo(Seminar::class, 'seminar_id'); }
}

