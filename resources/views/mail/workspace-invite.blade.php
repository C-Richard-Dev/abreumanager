<x-mail::message>
# Convite para o workspace {{ $workspace->name }}

{{ $sender->name }} convidou você para ser membro deste workspace.

<x-mail::button :url="$linkToAccept">
Aceitar convite
</x-mail::button>

Se você não esperava este convite, pode ignorar este e-mail.

Até logo,<br>
Cadastra Link
</x-mail::message>
