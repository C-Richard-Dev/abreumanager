<?php

namespace App\Actions\Links;

use App\Models\Link;

class CreateLink
{
    public function handle(array $inputs): Link
    {
        return Link::create(
            [
                'name' => $inputs['name'] ?? null,
                'url' => $inputs['url'],
                'workspace_id' => $inputs['workspace_id'],
                'category_id' => $inputs['category_id'] ?? null,
            ]
        );
    }
}