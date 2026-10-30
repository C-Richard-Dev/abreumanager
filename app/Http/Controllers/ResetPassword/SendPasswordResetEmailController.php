<?php

namespace App\Http\Controllers\ResetPassword;

use App\Actions\ResetPassword\SendEmailToReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendPasswordResetEmailController
{
    public function __invoke(Request $request, SendEmailToReset $sendEmailToReset): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $sendEmailToReset->execute($request->string('email')->toString());

        return back()->with('status', 'Password reset email sent.');
    }
}
