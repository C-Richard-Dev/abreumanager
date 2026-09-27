<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { index } from '@/routes/workspaces';
import Heading from '@/components/Heading.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';

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
    <Heading 
        class="mb-4 ml-4 mt-4"
        :title="workspace.name"
        ></Heading
    >
    <div class="mb-4">
        <Link
            :href="index.url()"
            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
        >
            Voltar
            <ArrowLeft class="inline-block ml-2" />
        </Link>
    </div>
    
</template>