<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';

interface Workspace {
  uuid: string;
  name: string;
}

const page = usePage<{
    workspaces: Workspace
}>()

const workspace = page.props.workspace;

defineOptions({
  title: 'test',
})

</script>
<template>
    test
</template>