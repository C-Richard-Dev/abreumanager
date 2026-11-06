<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkspaceInviteEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Workspace $workspace,
        public User $sender,
        public string $linkToAccept,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para o workspace '.$this->workspace->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.workspace-invite',
            with: [
                'linkToAccept' => $this->linkToAccept,
                'sender' => $this->sender,
                'workspace' => $this->workspace,
            ],
        );
    }
}
