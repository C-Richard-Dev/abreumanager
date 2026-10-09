<script setup lang="ts">
import { Plus, Link as LinkIcon } from '@lucide/vue';

interface LinkItem {
    uuid: string;
    name: string | null;
    url: string;
}

defineProps<{
    categoryName: string;
    links: LinkItem[];
}>();

function getInitials(value: string): string {
    return value
        .slice(0, 2)
        .toUpperCase();
}
</script>

<template>
    <section class="flex h-full min-h-[520px] flex-col rounded-xl border border-border bg-background p-3 shadow-sm">
        <div class="mb-3 flex items-center justify-between border-b border-border pb-3">
            <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground">
                Visualização
            </span>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-sm border border-border bg-background px-3 py-1.5 text-sm font-medium text-foreground transition hover:bg-muted"
            >
                <Plus class="size-4" />
                Adicionar link
            </button>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold tracking-tight text-foreground">
                {{ categoryName }}
            </h2>
        </div>

        <div class="flex flex-1 items-center justify-center">
            <div
                v-if="links.length === 0"
                class="flex h-full w-full flex-col items-center justify-center rounded-lg border border-dashed border-border bg-muted/30 p-6 text-center"
            >
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-muted text-foreground">
                    <LinkIcon class="size-5" />
                </div>
                <p class="text-lg font-medium text-foreground">
                    Nenhum link nesta categoria
                </p>
                <p class="mt-2 max-w-md text-sm text-muted-foreground">
                    Adicione novos links para começar a organizar seu workspace.
                </p>
            </div>

            <div v-else class="w-full space-y-3">
                <div
                    v-for="linkItem in links"
                    :key="linkItem.uuid"
                    class="flex items-center justify-between rounded-sm border border-border bg-background p-3"
                >
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-sm bg-muted text-xs font-semibold text-foreground">
                            {{ getInitials(linkItem.name || linkItem.url) }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-foreground">
                                {{ linkItem.name || 'Link sem nome' }}
                            </p>
                            <a
                                :href="linkItem.url"
                                target="_blank"
                                rel="noreferrer"
                                class="mt-1 block truncate text-xs text-muted-foreground hover:text-foreground"
                            >
                                {{ linkItem.url }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
