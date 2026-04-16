<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['full_name', 'email', 'password', 'avatar', 'phone_number', 'status', 'email_verified_at', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class Customer extends Model
{
    use SoftDeletes;
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
    //
}
