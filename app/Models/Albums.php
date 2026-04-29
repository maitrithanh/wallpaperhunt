<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description', 'like_count', 'view_count', 'thumbnail', 'status', 'wallpaper_count', 'partner_id'])]
class Albums extends Model
{
    use SoftDeletes;
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

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photos::class, 'album_id');
    }
}
