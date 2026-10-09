<?php

namespace App\Actions\Categories;

use App\Models\Category;

class DestroyCategory
{
    public function handle(Category $category): void
    {
        $category->delete();
    }
}