<x-mail::message>
# Olá, {{ $user->name }}!

Sua conta no **Cadastra Link** foi criada. Agora você pode manter seus links
importantes organizados em um só lugar.

<x-mail::button :url="$loginUrl">
Acessar minha conta
</x-mail::button>

Obrigado por se cadastrar,<br>
Cadastra Link
</x-mail::message>
