<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Head } from '@inertiajs/vue3';
import { index } from '@/routes/workspaces'

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

const workspaces = page.props.workspaces;

</script>
<template>
    <Head title="Workspaces" />
    <Heading 
        class="mb-4 ml-4 mt-4"
        title="Selecione um espaço de trabalho"
        ></Heading
    >
    <p v-if="workspaces.length === 0" class="ml-4">
        Nenhum espaço de trabalho encontrado.
    </p>
    <div v-else>
        <div
            v-for="workspace in workspaces"
            :key="workspace.uuid"
        >
            {{ workspace.name }}
        </div>
    </div>
</template>
