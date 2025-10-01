<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'detailed_description',
        'date',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'current_participants',
        'fee',
        'status',
        'category_id',
        'membership_requirement',
        'premium_open_at',
        'standard_open_at',
        'free_open_at',
        'featured_image',
        'application_deadline',
        'contact_email',
        'contact_phone',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'application_deadline' => 'date',
        'fee' => 'decimal:2',
        'premium_open_at' => 'datetime',
        'standard_open_at' => 'datetime',
        'free_open_at' => 'datetime',
    ];

    // 表示用の派生プロパティ
    protected $appends = ['featured_image_url'];

    // リレーションシップ
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function registrations()
    {
        return $this->hasMany(SeminarRegistration::class);
    }

    public function category()
    {
        return $this->belongsTo(SeminarCategory::class, 'category_id');
    }

    public function activeRegistrations()
    {
        return $this->hasMany(SeminarRegistration::class)
                    ->where('attendance_status', '!=', 'cancelled');
    }

    // スコープ
    public function scopePublished($query)
    {
        return $query->whereIn('status', ['scheduled', 'ongoing']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
                    ->where('status', 'scheduled');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByMembershipRequirement($query, $requirement)
    {
        return $query->where('membership_requirement', $requirement);
    }

    public function scopeDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->where('date', '>=', $from);
        }
        if ($to) {
            $query->where('date', '<=', $to);
        }
        return $query;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('date', '>=', now()->toDateString())
                    ->where(function ($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now()->toDateString());
                    });
    }

    // アクセサー・ミューテーター
    public function getFormattedDateAttribute()
    {
        return $this->date->format('Y年m月d日');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getFormattedFeeAttribute()
    {
        if ($this->fee == 0) {
            return '無料';
        }
        return number_format($this->fee) . '円';
    }

    public function getAvailableSpotsAttribute()
    {
        if ($this->capacity == 0) {
            return null; // 定員なし
        }
        return max(0, $this->capacity - $this->current_participants);
    }

    public function getIsFullAttribute()
    {
        if ($this->capacity == 0) {
            return false; // 定員なしの場合
        }
        return $this->current_participants >= $this->capacity;
    }

    public function getCanRegisterAttribute()
    {
        // ステータスチェック
        if ($this->status !== 'scheduled') {
            return false;
        }

        // 日付チェック
        if ($this->date < now()->toDateString()) {
            return false;
        }

        // 申込期限チェック
        if ($this->application_deadline && $this->application_deadline < now()->toDateString()) {
            return false;
        }

        // 定員チェック
        if ($this->is_full) {
            return false;
        }

        // 会員別の解禁日（存在する場合は最遅のfree_open_atを基準に最低限の公開判定）
        // ここでは会員種別は考慮せず、解禁日が全て未設定なら通過
        $earliest = $this->free_open_at ?? $this->standard_open_at ?? $this->premium_open_at;
        if ($earliest && now()->lt($earliest)) {
            return false;
        }

        return true;
    }

    /**
     * 画像のフルURL（/storage, 相対パスのいずれでも可）
     */
    public function getFeaturedImageUrlAttribute()
    {
        $path = $this->featured_image;

        if (!$path) {
            return '/img/image-1.png';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return $path;
        }
        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, 8);
        }
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }
        try {
            if (Storage::disk('public')->exists($normalized)) {
                return asset('storage/' . $normalized);
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return '/img/image-1.png';
    }

    // ヘルパーメソッド
    public function isUpcoming()
    {
        return $this->date >= now()->toDateString() && $this->status === 'scheduled';
    }

    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function updateParticipantCount()
    {
        $this->current_participants = $this->activeRegistrations()->count();
        $this->save();
    }

    public function canMemberRegister(Member $member = null)
    {
        if (!$this->can_register) {
            return false;
        }

        if (!$member) {
            // 一般公開（none / free）は未ログインでも申込可
            return in_array($this->membership_requirement, ['none', 'free'], true);
        }

        return $member->canRegisterSeminar($this);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'scheduled' => 'blue',
            'ongoing' => 'green',
            'completed' => 'purple',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getMembershipRequirementTextAttribute()
    {
        $requirements = [
            'none' => '制限なし',
            'basic' => 'ベーシック会員以上',
            'standard' => 'スタンダード会員以上',
            'premium' => 'プレミアム会員のみ',
        ];

        return $requirements[$this->membership_requirement] ?? '制限なし';
    }
}
