<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CircleCheck } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { login } from '@/routes';

defineOptions({
    layout: {
        title: 'Convite aceito',
        description: 'Você agora faz parte do workspace.',
    },
});
</script>

<template>
    <Head title="Convite aceito" />

    <div class="space-y-6">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-muted text-foreground">
            <CircleCheck class="size-5" aria-hidden="true" />
        </div>

        <p class="text-center text-sm text-muted-foreground">
            Convite aceito com sucesso! Faça login para acessar o workspace.
        </p>

        <Button as-child class="w-full">
            <Link :href="login()">Fazer Login</Link>
        </Button>
    </div>
</template>
