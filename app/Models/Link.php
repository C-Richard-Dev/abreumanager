<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Workspace;
use App\Models\Category;
use Illuminate\Support\Str;

#[Fillable(['uuid', 'workspace_id', 'category_id', 'name', 'url'])]
class Link extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Link $link): void {
            if (empty($link->uuid)) {
                $link->uuid = (string) Str::uuid();
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
