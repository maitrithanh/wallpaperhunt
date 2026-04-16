<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'content', 'note', 'amount', 'fee', 'status', 'partner_id'])]
class Withdraw extends Model
{
    use SoftDeletes;
    const STATUS_SUCCESS = 1;
    const STATUS_PENDING = 0;
    const STATUS_REJECTED = -1;

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_SUCCESS => 'Thành công',
            self::STATUS_PENDING => 'Đang chờ xử lý',
            self::STATUS_REJECTED => 'Bị từ chối',
        ];
    }
    //
}
