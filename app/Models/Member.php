<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'email_index',
        'password',
        'company_name',
        'representative_name',
        'position',
        'department',
        'phone',
        'postal_code',
        'address',
        'capital',
        'industry',
        'region',
        'concerns',
        'notes',
        'membership_type',
        'status',
        'joined_date',
        'started_at',
        'expiry_date',
        'membership_expires_at',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email' => 'encrypted',
        'representative_name' => 'encrypted',
        'joined_date' => 'date',
        'started_at' => 'date',
        'expiry_date' => 'date',
        'membership_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        // 機微情報はアプリキーで暗号化（検索に使わない項目のみ）
        'phone' => 'encrypted',
        'postal_code' => 'encrypted',
        'address' => 'encrypted',
        'position' => 'encrypted',
        'department' => 'encrypted',
        'concerns' => 'encrypted',
        'notes' => 'encrypted',
    ];

    // Keep a searchable/login index for email in plaintext (lowercased)
    public function setEmailAttribute($value)
    {
        // Ensure encrypted at write time (avoid storing plaintext when using mutator)
        if ($value === null || $value === '') {
            $this->attributes['email'] = null;
            $this->attributes['email_index'] = null;
            return;
        }
        $raw = (string)$value;
        try {
            // If already encrypted (e.g., backfill), keep as-is
            \Illuminate\Support\Facades\Crypt::decryptString($raw);
            $this->attributes['email'] = $raw;
        } catch (\Throwable $e) {
            // Encrypt plaintext
            $this->attributes['email'] = \Illuminate\Support\Facades\Crypt::encryptString($raw);
        }
        $this->attributes['email_index'] = mb_strtolower(trim($raw));
    }

    // リレーションシップ
    public function activityLogs()
    {
        return $this->hasMany(MemberActivityLog::class);
    }

    public function seminarRegistrations()
    {
        return $this->hasMany(SeminarRegistration::class);
    }

    public function publicationDownloads()
    {
        return $this->hasMany(PublicationDownload::class);
    }

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    // お気に入り関連のリレーション
    public function favorites()
    {
        return $this->hasMany(MemberFavorite::class, 'member_id');
    }

    public function favoritedBy()
    {
        return $this->hasMany(MemberFavorite::class, 'favorite_member_id');
    }

    // お気に入りの会員（多対多リレーション）
    public function favoriteMembers()
    {
        return $this->belongsToMany(Member::class, 'member_favorites', 'member_id', 'favorite_member_id')
                    ->withPivot('created_at');
    }

    // この会員をお気に入り登録している会員
    public function favoriteByMembers()
    {
        return $this->belongsToMany(Member::class, 'member_favorites', 'favorite_member_id', 'member_id')
                    ->withPivot('created_at');
    }

    // アクセサー・ミューテーター
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getFullNameAttribute()
    {
        return $this->representative_name;
    }

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByMembershipType($query, $type)
    {
        return $query->where('membership_type', $type);
    }

    public function scopeExpiring($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('status', 'active');
    }

    // ヘルパーメソッド
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function hasAccess($membershipLevel)
    {
        if (!$this->isActive()) {
            return false;
        }

        $levels = [
            'free' => 0,
            'basic' => 1,
            'standard' => 2,
            'premium' => 3,
        ];

        $currentLevel = $levels[$this->membership_type] ?? 0;
        $requiredLevel = $levels[$membershipLevel] ?? 0;

        return $currentLevel >= $requiredLevel;
    }

    public function canRegisterSeminar($seminar)
    {
        if (!$this->isActive()) {
            return false;
        }

        // セミナーの会員要件チェック
        if ($seminar->membership_requirement !== 'none') {
            if (!$this->hasAccess($seminar->membership_requirement)) {
                return false;
            }
        }

        // 定員チェック
        if ($seminar->capacity > 0 && $seminar->current_participants >= $seminar->capacity) {
            return false;
        }

        // 申込期限チェック
        if ($seminar->application_deadline && now()->gt($seminar->application_deadline)) {
            return false;
        }

        return true;
    }

    public function canDownloadPublication($publication)
    {
        if (!$this->isActive()) {
            return false;
        }

        return $this->hasAccess($publication->membership_level);
    }

    public function getMembershipLevelValue()
    {
        $levels = [
            'basic' => 1,
            'standard' => 2,
            'premium' => 3,
        ];

        return $levels[$this->membership_type] ?? 0;
    }

    public function isExpiringSoon($days = 30)
    {
        // 新しいフィールドを優先、フォールバックで古いフィールド
        $expiryDate = $this->membership_expires_at ?? $this->expiry_date;
        
        if (!$expiryDate) {
            return false;
        }

        return $expiryDate->diffInDays(now()) <= $days 
               && $expiryDate->isFuture();
    }

    /**
     * 会員期限が有効かチェック
     */
    public function membershipIsActive()
    {
        if ($this->membership_type === 'free') {
            return $this->is_active;
        }

        $expiryDate = $this->membership_expires_at ?? $this->expiry_date;

        return $this->is_active 
               && ($expiryDate === null || $expiryDate->isFuture());
    }

    /**
     * アクティブで有効期限内の会員のみを取得するスコープ
     */
    public function scopeActiveWithValidMembership($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('membership_type', 'free')
                          ->orWhere('membership_expires_at', '>', now())
                          ->orWhereNull('membership_expires_at')
                          ->orWhere(function($sub) {
                              // フォールバック: 古いフィールドもチェック
                              $sub->whereNull('membership_expires_at')
                                  ->where('expiry_date', '>', now());
                          });
                    });
    }
}
