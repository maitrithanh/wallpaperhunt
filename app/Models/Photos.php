<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'category_id', 'src', 'status', 'album_id', 'partner_id', 'price'])]
class Photos extends Model
{
    const STATUS_PUBLIC = 1;
    const STATUS_PRIVATE = 0;
    const STATUS_DEACTIVATED = -1;
    const STATUS_PENDING = 2;

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PUBLIC => 'Công khai',
            self::STATUS_PRIVATE => 'Riêng tư',
            self::STATUS_DEACTIVATED => 'Vô hiệu hóa',
            self::STATUS_PENDING => 'Đang chờ xử lý',
        ];
    }
    //
}
