<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description', 'like_count', 'view_count', 'category_id', 'src', 'status', 'album_id', 'partner_id', 'price'])]
class Photos extends Model
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

    protected static function booted()
    {
        static::saving(function ($photo) {
            if ($photo->status == self::STATUS_PUBLIC) {
                $category = $photo->category;
                if ($category && $category->status !== \App\Models\Category::STATUS_ACTIVE) {
                    throw new \Exception('Danh mục "' . $category->name . '" chưa được duyệt. Hãy duyệt danh mục trước!');
                }
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Albums::class, 'album_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function comments()
    {
        return $this->hasMany(PhotoComment::class, 'photo_id')->orderByDesc('created_at');
    }

    public function getSrcUrlAttribute()
    {
        if (!$this->src) {
            return 'https://picsum.photos/seed/' . $this->id . '/1920/1080';
        }
        if (str_starts_with($this->src, 'http')) {
            return $this->src;
        }
        if (str_starts_with($this->src, 'storage/')) {
            return asset($this->src);
        }
        return asset('storage/' . $this->src);
    }
}
