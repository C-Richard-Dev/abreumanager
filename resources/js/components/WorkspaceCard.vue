<script setup lang="ts">

import { show } from '@/routes/workspaces';

interface Workspace {
    id: number;
    uuid: string;
    name: string;
    created_at: string;
    updated_at: string;
}

defineProps<{
    workspace: Workspace;
}>();

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}   

</script>

<template>
    <a
        :href="show(workspace.uuid).url"
        class="block rounded-lg border p-4 transition hover:bg-accent"
    >
        <h2 class="font-semibold">
            {{ workspace.name }}
        </h2>
        <p class="text-sm text-muted-foreground">
            Criado em: {{ formatDate(workspace.created_at) }}
        </p>
    </a>
</template>
