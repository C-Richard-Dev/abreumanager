<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import { index } from '@/routes/workspaces';
import WorkspaceCategorySelector from '@/components/WorkspaceCategorySelector.vue';
import WorkspaceLinksPanel from '@/components/WorkspaceLinksPanel.vue';

interface Workspace {
    id?: number;
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
    if (selectedCategoryId.value === null || selectedCategoryId.value === 0) {
        return {
            id: 0,
            uuid: 'all',
            name: 'Todas',
            links: links.value,
        };
    }

    return categoryOptions.value.find((category) => category.id === selectedCategoryId.value) ?? null;
});

const visibleLinks = computed(() => {
    if (!selectedCategoryId.value || selectedCategoryId.value === 0) {
        return links.value;
    }

    return activeCategory.value?.links ?? [];
});

function handleCategoryCreated(category: { id: number; uuid: string; name: string }) {
    selectedCategoryId.value = category.id;

    router.reload({
        only: ['categories', 'links'],
    });
}
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
        </div>

        <div class="mb-5">
            <h1 class="text-left text-3xl font-semibold tracking-tight text-foreground">
                {{ workspace.name }}
            </h1>
            <p class="mt-2 text-left text-sm text-muted-foreground">
                Selecione uma categoria para visualizar os links do workspace.
            </p>
        </div>

        <div>
            <WorkspaceCategorySelector
                :categories="categoryOptions"
                :selected-category-id="selectedCategoryId"
                :workspace-uuid="workspace.uuid ?? null"
                @select-category="selectedCategoryId = $event"
                @category-created="handleCategoryCreated"
            />

            <WorkspaceLinksPanel
                :category-name="activeCategory?.name ?? 'Todas'"
                :links="visibleLinks"
                :workspace-uuid="workspace.uuid ?? null"
                :selected-category-id="selectedCategoryId"
                :categories="categories"
            />
        </div>
    </div>
</template>
