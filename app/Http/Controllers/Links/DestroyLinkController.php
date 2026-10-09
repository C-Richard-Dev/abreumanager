<?php

namespace App\Http\Controllers\Links;

use App\Actions\Links\DestroyLink;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;

class DestroyLinkController extends Controller
{
    public function __invoke(
        string $linkUuid,
        DestroyLink $destroyLink
    ): JsonResponse {
        $link = Link::where('uuid', $linkUuid)->firstOrFail();

        $destroyLink->handle($link);

        return response()->json(['message' => 'Link excluído com sucesso.']);
    }
}
