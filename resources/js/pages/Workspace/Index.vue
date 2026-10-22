<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Head } from '@inertiajs/vue3';
import { index, create } from '@/routes/workspaces'
import WorkspaceCard from '@/components/WorkspaceCard.vue';
import { computed } from 'vue';
import { Plus } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';


interface Workspace {
    id: number;
    uuid: string;
    name: string;
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

</script>
<template>
    <Head title="Workspaces" />
    <Heading 
        class="mb-4 ml-4 mt-4"
        title="Selecione um espaço de trabalho"
        ></Heading
    >
    <div class="mb-4">
        <Link
            :href="create()"
            class="inline-block rounded-sm border border-transparent ml-4 px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
        >
            Criar
            <Plus class="inline-block ml-2" />
        </Link>
    </div>
    
    <p v-if="workspaces.length === 0" class="ml-4">
        Nenhum espaço de trabalho encontrado.
    </p>
    <div v-else class="grid gap-4 px-4 md:grid-cols-2 lg:grid-cols-3">
            <WorkspaceCard
                v-for="workspace in workspaces"
                :key="workspace.uuid"
                :workspace="workspace"
            />
        </div>
</template>
