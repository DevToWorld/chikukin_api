<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Notification;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role',
        'is_active',
        'last_login_at',
        'failed_attempts',
        'locked_until',
        'mfa_enabled',
        'mfa_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'mfa_enabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // リレーションシップ
    public function createdPages()
    {
        return $this->hasMany(Page::class, 'created_by');
    }

    public function updatedPages()
    {
        return $this->hasMany(Page::class, 'updated_by');
    }

    public function createdSeminars()
    {
        return $this->hasMany(Seminar::class, 'created_by');
    }

    public function updatedSeminars()
    {
        return $this->hasMany(Seminar::class, 'updated_by');
    }

    public function createdPublications()
    {
        return $this->hasMany(Publication::class, 'created_by');
    }

    public function updatedPublications()
    {
        return $this->hasMany(Publication::class, 'updated_by');
    }

    public function assignedInquiries()
    {
        return $this->hasMany(Inquiry::class, 'assigned_to');
    }

    public function uploadedMedia()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    // アクセサー・ミューテーター
    // パスワードの自動ハッシュ化は AdminSeeder で Hash::make() を使用するため不要

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // ヘルパーメソッド
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }
    
    // is_adminプロパティのアクセサ（IsAdminミドルウェア用）
    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function canManage($resource)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // 各リソースに対する権限チェックのロジック
        return $this->isAdmin();
    }

    // Customize password reset notification for SPA route
    public function sendPasswordResetNotification($token)
    {
        $base = env('FRONTEND_ADMIN_RESET_URL', env('FRONTEND_URL', config('app.url')));
        $url = rtrim($base, '/') . '/#/admin/reset-password?token=' . urlencode($token) . '&email=' . urlencode($this->email);
        Notification::route('mail', $this->email)
            ->notify(new \App\Notifications\AdminResetPassword($url));
    }
}
