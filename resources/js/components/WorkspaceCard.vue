<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import {
    show,
    edit,
    destroy,
} from '@/routes/workspaces';

import { CalendarDays, Edit, Eye, FolderGit2, Trash2, User } from '@lucide/vue';

import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import ConfirmDialog from '@/components/ConfirmDialog.vue';

interface Workspace {
    id: number;
    uuid: string;
    name: string;
    created_by: number;
    creator: {
        id: number;
        name: string;
    };
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
    <Card class="gap-4 transition hover:border-primary/40 hover:shadow-md">
        <CardHeader class="flex flex-row items-center gap-3">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <FolderGit2 class="size-5" />
            </div>
            <div class="min-w-0">
                <CardTitle class="truncate">{{ workspace.name }}</CardTitle>
                <CardDescription class="flex items-center gap-1">
                    <User class="size-3.5" />
                    {{ workspace.creator.name }}
                </CardDescription>
            </div>
        </CardHeader>

        <CardContent>
            <p class="flex items-center gap-1.5 text-sm text-muted-foreground">
                <CalendarDays class="size-4" />
                Criado em {{ formatDate(workspace.created_at) }}
            </p>
        </CardContent>

        <CardFooter class="flex flex-wrap gap-2 border-t pt-4">
            <Button as-child size="sm">
                <Link :href="show(workspace.uuid).url">
                    <Eye class="size-4" />
                    Ver
                </Link>
            </Button>

            <Button as-child size="sm" variant="outline">
                <Link :href="edit(workspace.uuid).url">
                    <Edit class="size-4" />
                    Editar
                </Link>
            </Button>

            <Button
                type="button"
                size="sm"
                variant="ghost"
                class="ml-auto text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/30 dark:hover:text-red-300"
                @click="isDeleteModalOpen = true"
            >
                <Trash2 class="size-4" />
                Excluir
            </Button>
        </CardFooter>

        <ConfirmDialog
            v-model:is-open="isDeleteModalOpen"
            title="Excluir workspace"
            :message="`Tem certeza que deseja excluir o workspace '${workspace.name}'? Essa ação não poderá ser desfeita.`"
            confirm-text="Excluir"
            cancel-text="Cancelar"
            @confirm="handleDelete"
        />
    </Card>
</template>