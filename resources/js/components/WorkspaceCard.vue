<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import {
    show,
    edit,
    destroy,
} from '@/routes/workspaces';

import { Edit, Eye, Trash2 } from '@lucide/vue';

import ConfirmDialog from '@/components/ConfirmDialog.vue';

interface Workspace {
    id: number;
    uuid: string;
    name: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    workspace: Workspace;
}>();

const isDeleteModalOpen = ref(false);

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}

function handleDelete() {
    router.delete(
        destroy(props.workspace.uuid).url,
    );

    isDeleteModalOpen.value = false;
}
</script>

<template>
    <div class="block rounded-lg border p-4 transition hover:bg-accent">
        <h2 class="font-semibold">
            {{ workspace.name }}
        </h2>

        <p class="text-sm text-muted-foreground">
            Criado em: {{ formatDate(workspace.created_at) }}
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
            <Link
                :href="show(workspace.uuid).url"
                class="inline-flex items-center rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
            >
                Ver

                <Eye class="ml-2 size-4" />
            </Link>

            <Link
                :href="edit(workspace.uuid).url"
                class="inline-flex items-center rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
            >
                Editar

                <Edit class="ml-2 size-4" />
            </Link>

            <button
                type="button"
                class="inline-flex items-center rounded-sm px-5 py-1.5 text-sm leading-normal text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/30 dark:hover:text-red-300"
                @click="isDeleteModalOpen = true"
            >
                Excluir

                <Trash2 class="ml-2 size-4" />
            </button>
        </div>

        <ConfirmDialog
            v-model:is-open="isDeleteModalOpen"
            title="Excluir workspace"
            :message="`Tem certeza que deseja excluir o workspace '${workspace.name}'? Essa ação não poderá ser desfeita.`"
            confirm-text="Excluir"
            cancel-text="Cancelar"
            @confirm="handleDelete"
        />
    </div>
</template>