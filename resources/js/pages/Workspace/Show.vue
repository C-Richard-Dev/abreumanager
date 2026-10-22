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
    category?: {
        id?: number;
        uuid?: string;
        name?: string | null;
    } | null;
}

interface LinksPage {
    data: LinkItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const page = usePage<{
    workspace: Workspace;
    categories: Category[];
    links: LinksPage | LinkItem[];
    selectedCategoryId?: number | null;
    searchQuery?: string;
}>();

const workspace = computed(() => page.props.workspace);
const categories = computed(() => page.props.categories ?? []);
const searchTerm = ref(page.props.searchQuery ?? '');
const linksPage = computed<LinksPage>(() => {
    const rawLinks = page.props.links ?? { data: [] };

    if (Array.isArray(rawLinks)) {
        return {
            data: rawLinks,
            current_page: 1,
            last_page: 1,
            per_page: rawLinks.length || 10,
            total: rawLinks.length,
            from: rawLinks.length > 0 ? 1 : 0,
            to: rawLinks.length,
            prev_page_url: null,
            next_page_url: null,
        };
    }

    return {
        data: rawLinks.data ?? [],
        current_page: rawLinks.current_page ?? 1,
        last_page: rawLinks.last_page ?? 1,
        per_page: rawLinks.per_page ?? 10,
        total: rawLinks.total ?? 0,
        from: rawLinks.from ?? 0,
        to: rawLinks.to ?? 0,
        prev_page_url: rawLinks.prev_page_url ?? null,
        next_page_url: rawLinks.next_page_url ?? null,
    };
});
const links = computed(() => linksPage.value.data ?? []);
const selectedCategoryId = ref<number>(page.props.selectedCategoryId ?? 0);

const categoryOptions = computed(() => {
    return categories.value.map((category) => ({
        ...category,
        links: links.value.filter((link) => link.category_id === category.id),
    }));
});

const activeCategory = computed(() => {
    if (selectedCategoryId.value === 0) {
        return {
            id: 0,
            uuid: 'all',
            name: 'Todas',
            links: links.value,
        };
    }

    return categoryOptions.value.find((category) => category.id === selectedCategoryId.value) ?? null;
});

const visibleLinks = computed(() => links.value);

function navigateToPage(pageNumber: number, nextSearchTerm = searchTerm.value) {
    const params = new URLSearchParams(window.location.search);

    if (selectedCategoryId.value === 0) {
        params.delete('category_id');
    } else {
        params.set('category_id', String(selectedCategoryId.value));
    }

    if (nextSearchTerm.trim()) {
        params.set('search', nextSearchTerm.trim());
    } else {
        params.delete('search');
    }

    params.set('page', String(pageNumber));

    router.get(`${window.location.pathname}?${params.toString()}`, {}, {
        preserveScroll: true,
        preserveState: true,
        only: ['categories', 'links', 'selectedCategoryId', 'searchQuery'],
    });
}

function handleSearchChange(value: string) {
    searchTerm.value = value;
    navigateToPage(1, value);
}

function handleCategoryCreated(category: { id: number; uuid: string; name: string }) {
    selectedCategoryId.value = category.id;

    navigateToPage(1);
}

function handleCategorySelected(categoryId: number) {
    selectedCategoryId.value = categoryId;
    navigateToPage(1);
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
                @select-category="handleCategorySelected"
                @category-created="handleCategoryCreated"
            />

            <WorkspaceLinksPanel
                :category-name="activeCategory?.name ?? 'Todas'"
                :links="visibleLinks"
                :workspace-uuid="workspace.uuid ?? null"
                :selected-category-id="selectedCategoryId"
                :categories="categories"
                :pagination="linksPage"
                :search-query="searchTerm"
                @page-change="navigateToPage"
                @search-change="handleSearchChange"
            />
        </div>
    </div>
</template>
