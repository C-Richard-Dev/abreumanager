<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ShowInviteSuccessController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('invite/Success');
    }
}
