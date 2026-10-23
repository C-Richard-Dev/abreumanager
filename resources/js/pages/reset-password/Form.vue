<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { LockKeyhole } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Redefinir senha',
        description: 'Escolha uma nova senha para acessar sua conta.',
    },
});

const props = defineProps<{
    token: string;
}>();
</script>

<template>
    <Head title="Redefinir senha" />

    <div class="space-y-6">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-muted text-foreground">
            <LockKeyhole class="size-5" aria-hidden="true" />
        </div>

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token: props.token })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="space-y-5"
        >
            <InputError :message="errors.token" />

            <div class="grid gap-2">
                <Label for="password">Nova senha</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Digite sua nova senha"
                    minlength="8"
                    required
                />
                <InputError :message="errors.password" />
                <p class="text-xs text-muted-foreground">
                    Use pelo menos 8 caracteres.
                </p>
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar nova senha</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Digite a senha novamente"
                    minlength="8"
                    required
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button type="submit" class="w-full" :disabled="processing">
                <Spinner v-if="processing" />
                Redefinir senha
            </Button>
        </Form>
    </div>
</template>
