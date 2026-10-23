<h1>Redefinir Senha</h1>
<form method="POST" action="/reset-password">
    <input type="hidden" name="token" :value="token">
    <div>
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" required>
    </div>
    <div>
        <label for="password">Nova Senha</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirmar Senha</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Redefinir Senha</button>
</form>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    token: String
});
</script>