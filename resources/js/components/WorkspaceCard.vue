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

</script>

<template>
    <a
        :href="show(workspace.uuid).url"
        class="block rounded-lg border p-4 transition hover:bg-accent"
    >
        <h2 class="font-semibold">
            {{ workspace.name }}
        </h2>
    </a>
</template>
