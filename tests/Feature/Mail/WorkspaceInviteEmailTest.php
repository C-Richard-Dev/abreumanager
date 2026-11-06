<?php

use App\Mail\WorkspaceInviteEmail;
use App\Models\User;
use App\Models\Workspace;

test('workspace invite email includes the sender, workspace, and acceptance link', function () {
    $inviteEmail = new WorkspaceInviteEmail(
        new Workspace(['name' => 'Personal']),
        new User(['name' => 'Test Sender']),
        'https://example.test/invite/example-token',
    );

    $inviteEmail->assertSeeInHtml('Test Sender');
    $inviteEmail->assertSeeInHtml('Personal');
    $inviteEmail->assertSeeInHtml('Aceitar convite');
    $inviteEmail->assertSeeInHtml('https://example.test/invite/example-token');
    $inviteEmail->assertSeeInText('Test Sender');
});
