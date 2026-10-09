<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { ChevronDown, Pencil, Plus, Trash2 } from '@lucide/vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
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

const isDropdownOpen = ref(false);
const isCreateCategoryDialogOpen = ref(false);
const isEditCategoryDialogOpen = ref(false);
const isDeleteCategoryDialogOpen = ref(false);
const editingCategory = ref<CategoryItem | null>(null);
const deleteCategory = ref<CategoryItem | null>(null);
const editForm = ref({
    name: '',
    description: '',
});
const isSubmitting = ref(false);

const selectedCategory = computed(() => {
    return props.categories.find((category) => category.id === props.selectedCategoryId) ?? props.categories[0] ?? null;
});

const selectedLabel = computed(() => {
    if (props.selectedCategoryId === null) {
        return 'Todas';
    }

    return selectedCategory.value?.name ?? 'Todas';
});

function getCsrfToken(): string {
    const cookie = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='));

    if (!cookie) {
        return '';
    }

    return decodeURIComponent(cookie.split('=')[1]);
}

function selectCategory(categoryId: number) {
    isDropdownOpen.value = false;
    emit('select-category', categoryId);
}

function openEditCategory(category: CategoryItem) {
    editingCategory.value = category;
    editForm.value = {
        name: category.name,
        description: category.description ?? '',
    };
    isEditCategoryDialogOpen.value = true;
    isDropdownOpen.value = false;
}

function closeEditCategory() {
    isEditCategoryDialogOpen.value = false;
    editingCategory.value = null;
    editForm.value = { name: '', description: '' };
}

function openDeleteCategory(category: CategoryItem) {
    deleteCategory.value = category;
    isDeleteCategoryDialogOpen.value = true;
    isDropdownOpen.value = false;
}

function closeDeleteCategory() {
    isDeleteCategoryDialogOpen.value = false;
    deleteCategory.value = null;
}

async function submitCategoryUpdate() {
    if (!editingCategory.value) {
        return;
    }

    const name = editForm.value.name.trim();

    if (!name) {
        toast.error('Informe o nome da categoria.');
        return;
    }

    try {
        isSubmitting.value = true;

        const response = await fetch(`/categories/${editingCategory.value.uuid}/update`, {
            method: 'PUT',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-XSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                name,
                description: editForm.value.description.trim() || null,
            }),
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => null);
            throw new Error(payload?.message ?? 'Não foi possível atualizar a categoria.');
        }

        toast.success('Categoria atualizada com sucesso.');
        router.reload({ only: ['categories', 'links'] });
        closeEditCategory();
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Erro ao atualizar categoria.');
    } finally {
        isSubmitting.value = false;
    }
}

async function confirmDeleteCategory() {
    if (!deleteCategory.value) {
        return;
    }

    try {
        isSubmitting.value = true;

        const response = await fetch(`/categories/${deleteCategory.value.uuid}/destroy`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-XSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => null);
            throw new Error(payload?.message ?? 'Não foi possível excluir a categoria.');
        }

        toast.success('Categoria removida com sucesso.');
        router.reload({ only: ['categories', 'links'] });
        closeDeleteCategory();
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Erro ao excluir categoria.');
    } finally {
        isSubmitting.value = false;
    }
}

function handleCategoryCreated(category: { id: number; uuid: string; name: string }) {
    emit('category-created', category);
}
</script>

<template>
    <div class="mb-5 flex items-center gap-2">
        <div class="relative flex-1">
            <button
                type="button"
                class="flex w-full items-center justify-between rounded-sm border border-border bg-background px-4 py-2.5 text-left transition hover:bg-muted"
                @click="isDropdownOpen = !isDropdownOpen"
            >
                <div class="flex min-w-0 flex-col">
                    <span class="text-[10px] font-medium uppercase tracking-[0.2em] text-muted-foreground">
                        Categoria
                    </span>
                    <span class="truncate text-sm font-medium text-foreground">
                        {{ selectedLabel }}
                    </span>
                </div>

                <ChevronDown class="size-4 shrink-0 text-muted-foreground" />
            </button>

            <div
                v-if="isDropdownOpen"
                class="absolute left-0 top-full z-20 mt-2 w-full overflow-hidden rounded-sm border border-border bg-background shadow-sm"
            >
                <button
                    type="button"
                    class="flex w-full items-center justify-between border-b border-border px-3 py-3 text-left transition hover:bg-muted"
                    @click="selectCategory(0)"
                >
                    <div>
                        <p class="text-sm font-medium text-foreground">Todas</p>
                        <p class="text-[11px] text-muted-foreground">Todos os links do workspace</p>
                    </div>
                </button>

                <div
                    v-for="category in categories"
                    :key="category.uuid"
                    class="flex items-center justify-between border-b border-border last:border-b-0"
                >
                    <button
                        type="button"
                        class="flex flex-1 items-center justify-between px-3 py-3 text-left transition hover:bg-muted"
                        @click="selectCategory(category.id)"
                    >
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-foreground">
                                {{ category.name }}
                            </p>
                            <p
                                v-if="category.description"
                                class="mt-1 truncate text-[11px] text-muted-foreground"
                            >
                                {{ category.description }}
                            </p>
                        </div>
                    </button>

                    <div class="flex items-center gap-1 px-2">
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-sm border border-border text-foreground transition hover:bg-muted"
                            @click.stop="openEditCategory(category)"
                            aria-label="Editar categoria"
                        >
                            <Pencil class="size-3.5" />
                        </button>
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-sm border border-border text-foreground transition hover:bg-muted"
                            @click.stop="openDeleteCategory(category)"
                            aria-label="Excluir categoria"
                        >
                            <Trash2 class="size-3.5" />
                        </button>
                    </div>
                </div>
            </div>
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

    <Dialog :open="isEditCategoryDialogOpen" @update:open="isEditCategoryDialogOpen = $event">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Editar categoria
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Ajuste o nome e a descrição desta categoria.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-5 py-2">
                <div class="space-y-2">
                    <label for="edit-category-name" class="text-sm font-medium text-foreground">
                        Nome
                    </label>
                    <input
                        id="edit-category-name"
                        v-model="editForm.name"
                        type="text"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="edit-category-description" class="text-sm font-medium text-foreground">
                        Descrição
                    </label>
                    <textarea
                        id="edit-category-description"
                        v-model="editForm.description"
                        rows="4"
                        class="w-full resize-none rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>
            </div>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button type="button" variant="outline" :disabled="isSubmitting" @click="closeEditCategory()">
                    Cancelar
                </Button>
                <Button type="button" :disabled="isSubmitting" @click="submitCategoryUpdate()">
                    {{ isSubmitting ? 'Salvando...' : 'Salvar alterações' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <Dialog :open="isDeleteCategoryDialogOpen" @update:open="isDeleteCategoryDialogOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Excluir categoria
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Tem certeza que deseja remover <strong class="font-medium text-foreground">{{ deleteCategory?.name }}</strong>? Esta ação não pode ser desfeita.
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button type="button" variant="outline" :disabled="isSubmitting" @click="closeDeleteCategory()">
                    Cancelar
                </Button>
                <Button type="button" variant="destructive" :disabled="isSubmitting" @click="confirmDeleteCategory()">
                    {{ isSubmitting ? 'Excluindo...' : 'Excluir' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
