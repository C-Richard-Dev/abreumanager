<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * @property string $uuid
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 */
#[Fillable(['uuid', 'name'])]
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
}
