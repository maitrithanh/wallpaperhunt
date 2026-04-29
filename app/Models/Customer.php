<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['full_name', 'email', 'password', 'avatar', 'phone_number', 'status', 'email_verified_at', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class Customer extends Model
{
    use SoftDeletes, HasApiTokens;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;
    const STATUS_SUSPENDED = 2;
    const STATUS_PENDING = 3;

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Không hoạt động',
            self::STATUS_SUSPENDED => 'Tạm ngưng',
            self::STATUS_DELETED => 'Đã xóa',
            self::STATUS_PENDING => 'Đang chờ xử lý',
        ];
    }
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=random';
        }
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        if (str_starts_with($this->avatar, 'storage/')) {
            return asset($this->avatar);
        }
        return asset('storage/' . $this->avatar);
    }
}
