<?php

namespace App\Http\Controllers\Categories;

use App\Actions\Categories\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class UpdateCategoryController extends Controller
{
    public function __invoke(
        UpdateCategoryRequest $request,
        string $categoryUuid,
        UpdateCategory $updateCategory
    ): JsonResponse {
        $category = Category::where('uuid', $categoryUuid)->firstOrFail();

        $inputs = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $updateCategory->handle($category, $inputs);

        return response()->json(['message' => 'Category updated successfully']);
    }
}
