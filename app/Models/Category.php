<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
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
    //
}
