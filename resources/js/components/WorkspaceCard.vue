<script setup lang="ts">

import { Link } from '@inertiajs/vue3';
import { show, edit } from '@/routes/workspaces';
import { Edit, Eye } from '@lucide/vue';

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
    <div
        class="block rounded-lg border p-4 transition hover:bg-accent cursor-pointer"
    >
        <h2 class="font-semibold">
            {{ workspace.name }}
        </h2>
        <p class="text-sm text-muted-foreground">
            Criado em: {{ formatDate(workspace.created_at) }}
        </p>
        <Link
            :href="show(workspace.uuid).url"
            class="inline-block rounded-sm mt-4 border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
        >
            Ver
            <Eye class="inline-block ml-2" />
        </Link>
        <Link
            :href="edit(workspace.uuid).url"
            class="inline-block rounded-sm mt-4 border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
        >
            Editar
            <Edit class="inline-block ml-2" />
        </Link>
    </div>
</template>
