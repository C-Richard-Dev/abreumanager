<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Head } from '@inertiajs/vue3';
import { index, create } from '@/routes/workspaces'
import WorkspaceCard from '@/components/WorkspaceCard.vue';
import { computed, ref } from 'vue';
import { Plus, Search } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Link } from '@inertiajs/vue3';


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

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Workspaces',
                href: index(),
            },
        ],
    },
});

const page = usePage<{
    workspaces: Workspace[]
}>()

const workspaces = computed(() => page.props.workspaces);
const search = ref('');

const filteredWorkspaces = computed(() => {
    const term = search.value.trim().toLowerCase();

    return term === ''
        ? workspaces.value
        : workspaces.value.filter((workspace) =>
              workspace.name.toLowerCase().includes(term),
          );
});

</script>
<template>
    <Head title="Workspaces" />
    <Heading 
        class="mb-4 ml-4 mt-4"
        title="Selecione um espaço de trabalho"
        ></Heading
    >
    <div class="mb-4 flex flex-col gap-3 px-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative w-full sm:max-w-sm">
            <Search class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
                v-model="search"
                type="search"
                placeholder="Pesquisar workspace..."
                class="pl-9"
            />
        </div>

        <Button as-child class="gap-2">
            <Link :href="create()">
                <Plus class="size-4" />
                Criar
            </Link>
        </Button>
    </div>

    <p v-if="filteredWorkspaces.length === 0" class="ml-4">
        Nenhum espaço de trabalho encontrado.
    </p>
    <div v-else class="grid gap-4 px-4 md:grid-cols-2 lg:grid-cols-3">
            <WorkspaceCard
                v-for="workspace in filteredWorkspaces"
                :key="workspace.uuid"
                :workspace="workspace"
            />
        </div>
</template>
