<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'membership_type',
        'membership_expires_at',
        'membership_features',
        'is_active',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'membership_expires_at' => 'datetime',
        'membership_features' => 'array',
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
    ];

    const MEMBERSHIP_FREE = 'free';
    const MEMBERSHIP_STANDARD = 'standard';
    const MEMBERSHIP_PREMIUM = 'premium';

    public function isFree(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_FREE;
    }

    public function isStandard(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_STANDARD;
    }

    public function isPremium(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_PREMIUM;
    }

    public function hasPremiumAccess(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_PREMIUM;
    }

    public function hasStandardAccess(): bool
    {
        return in_array($this->membership_type, [self::MEMBERSHIP_STANDARD, self::MEMBERSHIP_PREMIUM]);
    }

    public function membershipIsActive(): bool
    {
        if ($this->membership_type === self::MEMBERSHIP_FREE) {
            return true;
        }

        return $this->membership_expires_at === null || $this->membership_expires_at->isFuture();
    }

    public function canAccessFeature(string $feature): bool
    {
        if (!$this->membershipIsActive()) {
            return false;
        }

        if ($this->membership_features && in_array($feature, $this->membership_features)) {
            return true;
        }

        $defaultFeatures = $this->getDefaultFeaturesByMembership();
        return in_array($feature, $defaultFeatures);
    }

    public function getDefaultFeaturesByMembership(): array
    {
        switch ($this->membership_type) {
            case self::MEMBERSHIP_PREMIUM:
                return [
                    'economic_statistics_full',
                    'financial_reports_full',
                    'news_full',
                    'api_access',
                    'data_export',
                    'custom_reports',
                    'priority_support',
                ];
            case self::MEMBERSHIP_STANDARD:
                return [
                    'economic_statistics_limited',
                    'financial_reports_limited',
                    'news_full',
                    'data_export_limited',
                ];
            default:
                return [
                    'economic_statistics_basic',
                    'news_limited',
                ];
        }
    }
}
