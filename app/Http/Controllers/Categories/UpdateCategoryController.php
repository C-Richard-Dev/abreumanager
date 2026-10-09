<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Actions\Categories\UpdateCategory;

class UpdateCategoryController extends Controller
{
    public function __invoke(
        UpdateCategoryRequest $request, 
        string $categoryUuid,
        UpdateCategory $updateCategory
    ): \Illuminate\Http\JsonResponse {
        $category = Category::where('uuid', $categoryUuid)->firstOrFail();

        $inputs = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $updateCategory->handle($category, $inputs);

        return response()->json(['message' => 'Category updated successfully']);
    }
}
