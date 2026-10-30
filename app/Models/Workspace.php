<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $uuid
 * @property string $name
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['uuid', 'name', 'created_by'])]
class Workspace extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Workspace $workspace): void {
            if (empty($workspace->uuid)) {
                $workspace->uuid = (string) Str::uuid();
            }
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_workspaces');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function links(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
