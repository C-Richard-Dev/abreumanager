<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Link as LinkIcon, Plus } from '@lucide/vue';
import { index } from '@/routes/workspaces';

interface Workspace {
    uuid: string;
    name: string;
}

interface Category {
    id: number;
    uuid: string;
    name: string;
    description?: string | null;
}

interface LinkItem {
    id: number;
    uuid: string;
    name: string | null;
    url: string;
    category_id?: number | null;
}

const page = usePage<{
    workspace: Workspace;
    categories: Category[];
    links: LinkItem[];
}>();

const workspace = computed(() => page.props.workspace);
const categories = computed(() => page.props.categories ?? []);
const links = computed(() => page.props.links ?? []);
const selectedCategoryId = ref<number | null>(null);

const categoryOptions = computed(() => {
    const normalized = categories.value.map((category) => ({
        ...category,
        links: links.value.filter((link) => link.category_id === category.id),
    }));

    const uncategorized = links.value.filter((link) => !link.category_id);

    if (uncategorized.length > 0) {
        normalized.unshift({
            id: 0,
            uuid: 'uncategorized',
            name: 'Sem categoria',
            description: 'Links ainda não agrupados',
            links: uncategorized,
        });
    }

    if (selectedCategoryId.value === null && normalized.length > 0) {
        selectedCategoryId.value = normalized[0].id;
    }

    return normalized;
});

const activeCategory = computed(() => {
    return categoryOptions.value.find((category) => category.id === selectedCategoryId.value) ?? categoryOptions.value[0] ?? null;
});

const visibleLinks = computed(() => activeCategory.value?.links ?? []);
</script>

<template>
    <Head :title="workspace.name" />

    <div class="flex min-h-[calc(100vh-72px)] flex-col px-4 py-5 lg:px-8">
        <div class="mb-5 flex items-center justify-between">
            <Link
                :href="index.url()"
                class="inline-flex items-center gap-2 rounded-sm border border-border bg-background px-4 py-2 text-sm font-medium leading-normal text-foreground transition hover:bg-muted"
            >
                <ArrowLeft class="size-4" />
                Voltar
            </Link>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-sm bg-foreground px-4 py-2 text-sm font-medium leading-normal text-background transition hover:opacity-90"
            >
                <Plus class="size-4" />
                Novo link
            </button>
        </div>

        <div class="mb-5">
            <h1 class="text-left text-3xl font-semibold tracking-tight text-foreground">
                {{ workspace.name }}
            </h1>
            <p class="mt-2 text-left text-sm text-muted-foreground">
                Selecione uma categoria para visualizar os links do workspace.
            </p>
        </div>

        <div class="grid flex-1 gap-5 lg:grid-cols-[minmax(300px,0.75fr)_minmax(0,1.8fr)]">
            <aside class="flex h-full min-h-[520px] flex-col rounded-xl border border-border bg-background p-3 shadow-sm">
                <div class="mb-3 flex items-center justify-between border-b border-border pb-3">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground">
                        Categorias
                    </span>

                    <button
                        type="button"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-sm border border-border bg-background text-foreground transition hover:bg-muted"
                    >
                        <Plus class="size-4" />
                    </button>
                </div>

                <div v-if="categoryOptions.length" class="flex-1 space-y-2 overflow-y-auto">
                    <button
                        v-for="category in categoryOptions"
                        :key="category.uuid"
                        type="button"
                        @click="selectedCategoryId = category.id"
                        class="flex w-full items-center justify-between rounded-sm border px-3 py-3 text-left transition"
                        :class="category.id === selectedCategoryId
                            ? 'border-foreground bg-foreground text-background'
                            : 'border-border bg-background text-foreground hover:bg-muted'"
                    >
                        <div class="min-w-0 pr-2">
                            <p class="truncate text-sm font-medium">{{ category.name }}</p>
                            <p
                                v-if="category.description"
                                class="mt-1 truncate text-[11px]"
                                :class="category.id === selectedCategoryId ? 'text-background/80' : 'text-muted-foreground'"
                            >
                                {{ category.description }}
                            </p>
                        </div>

                        <span
                            class="inline-flex min-w-[22px] justify-center rounded-full px-2 py-0.5 text-xs font-medium"
                            :class="category.id === selectedCategoryId ? 'bg-background/10 text-current' : 'bg-muted text-foreground'"
                        >
                            {{ category.links.length }}
                        </span>
                    </button>
                </div>

                <div
                    v-else
                    class="flex flex-1 items-center justify-center rounded-lg border border-dashed border-border bg-background text-center text-sm text-foreground"
                >
                    Nenhuma categoria cadastrada.
                </div>
            </aside>

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

                <div class="flex flex-1 items-center justify-center">
                    <div
                        v-if="visibleLinks.length === 0"
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
                            v-for="linkItem in visibleLinks"
                            :key="linkItem.uuid"
                            class="flex items-center justify-between rounded-sm border border-border bg-background p-3"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-sm bg-muted text-xs font-semibold text-foreground">
                                    {{ (linkItem.name || linkItem.url).slice(0, 2).toUpperCase() }}
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
        </div>
    </div>
</template>
