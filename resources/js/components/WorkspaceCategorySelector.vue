<script setup lang="ts">
import { computed, ref } from 'vue';
import { ChevronDown, Plus } from '@lucide/vue';
import WorkspaceCategoryCreateDialog from '@/components/WorkspaceCategoryCreateDialog.vue';

interface CategoryItem {
    id: number;
    uuid: string;
    name: string;
    description?: string | null;
    links: Array<{ uuid: string }>;
}

const props = defineProps<{
    categories: CategoryItem[];
    selectedCategoryId: number | null;
    workspaceUuid: string | null;
}>();

const emit = defineEmits<{
    (event: 'select-category', categoryId: number): void;
    (event: 'category-created', category: { id: number; uuid: string; name: string }): void;
}>();

const isCreateCategoryDialogOpen = ref(false);

const selectedCategory = computed(() => {
    return props.categories.find((category) => category.id === props.selectedCategoryId) ?? props.categories[0] ?? null;
});

function handleCategoryChange(event: Event) {
    const target = event.target as HTMLSelectElement;
    const nextValue = Number(target.value);

    if (Number.isNaN(nextValue)) {
        return;
    }

    emit('select-category', nextValue);
}

function handleCategoryCreated(category: { id: number; uuid: string; name: string }) {
    emit('category-created', category);
}
</script>

<template>
    <div class="mb-5 flex items-center gap-2">
        <div class="relative flex-1">
            <select
                :value="selectedCategoryId ?? ''"
                class="w-full appearance-none rounded-sm border border-border bg-background px-4 py-2.5 pr-10 text-sm font-medium text-foreground outline-none transition focus:border-foreground"
                @change="handleCategoryChange"
            >
                <option value="">Todas</option>
                <option
                    v-for="category in categories"
                    :key="category.uuid"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>

            <ChevronDown class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        </div>

        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-sm border border-border bg-background px-4 py-2.5 text-sm font-medium text-foreground transition hover:bg-muted"
            @click="isCreateCategoryDialogOpen = true"
        >
            <Plus class="size-4" />
            Criar categoria
        </button>
    </div>

    <WorkspaceCategoryCreateDialog
        v-model:is-open="isCreateCategoryDialogOpen"
        :workspace-uuid="workspaceUuid"
        @created="handleCategoryCreated"
    />
</template>
