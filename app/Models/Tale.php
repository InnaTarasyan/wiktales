<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tale extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'source_filename',
        'cover_url',
        'toc',
        'meta',
    ];

    protected $casts = [
        'toc' => 'array',
        'meta' => 'array',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(TaleSection::class)->orderBy('order');
    }
}
