<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { LockKeyhole } from '@lucide/vue';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        title: 'Redefinir senha',
        description: 'Escolha uma nova senha para acessar sua conta.',
    },
});

const props = defineProps<{
    token: string;
    email?: string;
}>();

const email = ref(props.email ?? '');
const password = ref('');
const passwordConfirmation = ref('');
const passwordError = ref('');
const formNotice = ref('');

function handleSubmit(): void {
    passwordError.value = '';
    formNotice.value = '';

    if (password.value !== passwordConfirmation.value) {
        passwordError.value = 'As senhas não coincidem.';
        return;
    }

    formNotice.value = 'O envio ainda não está conectado ao servidor.';
}
</script>

<template>
    <Head title="Redefinir senha" />

    <div class="space-y-6">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-muted text-foreground">
            <LockKeyhole class="size-5" aria-hidden="true" />
        </div>

        <form class="space-y-5" @submit.prevent="handleSubmit">
            <input type="hidden" name="token" :value="props.token" />

            <div class="grid gap-2">
                <Label for="email">E-mail</Label>
                <Input
                    id="email"
                    v-model="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    placeholder="voce@exemplo.com"
                    required
                />
            </div>

            <div class="grid gap-2">
                <Label for="password">Nova senha</Label>
                <PasswordInput
                    id="password"
                    v-model="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Digite sua nova senha"
                    minlength="8"
                    required
                />
                <p class="text-xs text-muted-foreground">
                    Use pelo menos 8 caracteres.
                </p>
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar nova senha</Label>
                <PasswordInput
                    id="password_confirmation"
                    v-model="passwordConfirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Digite a senha novamente"
                    minlength="8"
                    required
                />
                <InputError :message="passwordError" />
            </div>

            <Button type="submit" class="w-full">
                Redefinir senha
            </Button>

            <p
                v-if="formNotice"
                role="status"
                class="text-center text-sm text-muted-foreground"
            >
                {{ formNotice }}
            </p>
        </form>
    </div>
</template>
