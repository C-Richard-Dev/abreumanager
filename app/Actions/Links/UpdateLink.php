<?php

namespace App\Actions\Links;

use App\Models\Link;

class UpdateLink
{
    public function handle(Link $link, array $inputs): Link
    {
        $link->update(
            [
                'name' => $inputs['name'] ?? null,
                'url' => $inputs['url'],
                'workspace_id' => $inputs['workspace_id'],
                'category_id' => $inputs['category_id'] ?? null,
            ]
        );

        return $link;
    }
}