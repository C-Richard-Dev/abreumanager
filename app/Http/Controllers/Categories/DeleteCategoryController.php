<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Actions\Categories\DestroyCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class DeleteCategoryController extends Controller
{
    public function __invoke(
        string $categoryUuid,
        DestroyCategory $destroyCategory
    ): \Illuminate\Http\JsonResponse {
        $category = Category::where('uuid', $categoryUuid)->firstOrFail();

        $destroyCategory->handle($category);

        return response()->json(['message' => 'Categoria excluída com sucesso.']);
    }
}
