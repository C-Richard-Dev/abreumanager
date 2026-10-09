<?php

namespace App\Actions\Links;

use App\Models\Link;

class DestroyLink
{
    public function handle(Link $link): void
    {
        $link->delete();
    }
}