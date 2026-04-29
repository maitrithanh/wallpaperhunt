<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'avatar', 'slug', 'description', 'status'])]
class Category extends Model
{
    use SoftDeletes;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DRAFT = 2;
    const STATUS_PENDING = 3;

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Không hoạt động',
            self::STATUS_DRAFT => 'Nháp',
            self::STATUS_PENDING => 'Chờ duyệt',
        ];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photos::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return 'https://picsum.photos/seed/' . $this->id . '/300/300';
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
