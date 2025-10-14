<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaleSection extends Model
{
    protected $fillable = [
        'tale_id',
        'order',
        'title',
        'anchor',
        'body_html',
        'body_text',
    ];

    public function tale(): BelongsTo
    {
        return $this->belongsTo(Tale::class);
    }
}
