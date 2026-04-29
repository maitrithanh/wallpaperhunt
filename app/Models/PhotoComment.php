<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoComment extends Model
{
    protected $fillable = ['photo_id', 'customer_id', 'author_name', 'content'];

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photos::class, 'photo_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
