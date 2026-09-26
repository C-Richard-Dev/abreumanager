<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

interface Workspace {
  uuid: string;
  name: string;
}

const page = usePage<{
    workspace: Workspace
}>()

const workspace = page.props.workspace;

</script>
<template>
    <Head title="Workspace" />
    <h1>{{ workspace.name }}</h1>
</template>