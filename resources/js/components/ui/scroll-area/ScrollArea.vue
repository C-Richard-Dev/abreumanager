<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div
        data-slot="scroll-area"
        :class="cn('min-h-0 overflow-y-auto', props.class)"
    >
        <slot />
    </div>
</template>

<style scoped>
[data-slot="scroll-area"] {
    scrollbar-color: color-mix(in srgb, var(--muted-foreground) 40%, var(--background)) transparent;
    scrollbar-width: thin;
}

[data-slot="scroll-area"]::-webkit-scrollbar {
    width: 6px;
}

[data-slot="scroll-area"]::-webkit-scrollbar-track {
    background: transparent;
}

[data-slot="scroll-area"]::-webkit-scrollbar-thumb {
    border-radius: 9999px;
    background-color: color-mix(in srgb, var(--muted-foreground) 40%, var(--background));
}

[data-slot="scroll-area"]::-webkit-scrollbar-thumb:hover {
    background-color: color-mix(in srgb, var(--muted-foreground) 60%, var(--background));
}
</style>
