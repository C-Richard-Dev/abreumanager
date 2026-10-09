<?php

namespace App\Actions\Categories;

use App\Models\Category;

class UpdateCategory
{
    public function handle(Category $category, array $inputs): void
    {
        $category->update(
            [
                'name' => $inputs['name'],
                'description' => $inputs['description'],
            ]
        );
    }
}