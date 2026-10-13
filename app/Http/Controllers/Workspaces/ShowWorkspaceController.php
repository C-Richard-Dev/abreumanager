<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workspace;

class ShowWorkspaceController extends Controller
{
    public function __invoke(string $workspaceUuid, Request $request): \Inertia\Response
    {
        $workspace = Workspace::where('uuid', $workspaceUuid)
            ->firstOrFail();

        $selectedCategoryId = $request->query('category_id');
        $search = trim((string) $request->query('search', ''));
        $page = max(1, (int) $request->query('page', 1));

        $linksQuery = $workspace->links()->orderBy('created_at', 'desc');

        if ($selectedCategoryId !== null && $selectedCategoryId !== '' && $selectedCategoryId !== '0') {
            $linksQuery->where('category_id', (int) $selectedCategoryId);
        }

        if ($search !== '') {
            $linksQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        return inertia('Workspace/Show', [
            'workspace' => $workspace,
            'categories' => $workspace->categories()->orderBy('name')->get(),
            'links' => $linksQuery->paginate(10, ['*'], 'page', $page)->appends($request->only(['category_id', 'search'])),
            'selectedCategoryId' => $selectedCategoryId === null || $selectedCategoryId === '' ? 0 : (int) $selectedCategoryId,
            'searchQuery' => $search,
        ]);
    }
}