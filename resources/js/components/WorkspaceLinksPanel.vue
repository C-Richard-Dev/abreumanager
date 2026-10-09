<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Link as LinkIcon, Pencil, Plus, Trash2 } from '@lucide/vue';
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

interface LinkItem {
    id: number;
    uuid: string;
    name: string | null;
    url: string;
    category_id?: number | null;
}

interface CategoryOption {
    id: number;
    uuid: string;
    name: string;
}

const props = defineProps<{
    categoryName: string;
    links: LinkItem[];
    workspaceUuid: string | null;
    selectedCategoryId: number | null;
    categories: CategoryOption[];
}>();

const isCreateDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const isSubmitting = ref(false);
const editingLink = ref<LinkItem | null>(null);
const deletingLink = ref<LinkItem | null>(null);
const form = ref({
    name: '',
    url: '',
    category_id: null as number | null,
});

const defaultCategoryId = computed(() => {
    if (props.selectedCategoryId && props.selectedCategoryId !== 0) {
        return props.selectedCategoryId;
    }

    return null;
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

function getInitials(value: string): string {
    return value
        .slice(0, 2)
        .toUpperCase();
}

function resetForm() {
    form.value = {
        name: '',
        url: '',
        category_id: defaultCategoryId.value,
    };
}

function openCreateDialog() {
    editingLink.value = null;
    resetForm();
    isCreateDialogOpen.value = true;
}

function openEditLink(link: LinkItem) {
    editingLink.value = link;
    form.value = {
        name: link.name ?? '',
        url: link.url,
        category_id: link.category_id ?? null,
    };
    isEditDialogOpen.value = true;
}

function openDeleteLink(link: LinkItem) {
    deletingLink.value = link;
    isDeleteDialogOpen.value = true;
}

function closeCreateDialog() {
    isCreateDialogOpen.value = false;
    resetForm();
}

function closeEditDialog() {
    isEditDialogOpen.value = false;
    editingLink.value = null;
    resetForm();
}

function closeDeleteDialog() {
    isDeleteDialogOpen.value = false;
    deletingLink.value = null;
}

async function submitLink() {
    if (!props.workspaceUuid) {
        toast.error('Workspace não foi identificado.');
        return;
    }

    const url = form.value.url.trim();
    const name = form.value.name.trim();

    if (!url) {
        toast.error('Informe a URL do link.');
        return;
    }

    try {
        new URL(url);
    } catch {
        toast.error('A URL informada não é válida.');
        return;
    }

    const payload = {
        name: name || null,
        url,
        category_id: form.value.category_id && form.value.category_id !== 0 ? form.value.category_id : null,
    };

    const endpoint = editingLink.value
        ? `/links/${editingLink.value.uuid}/update`
        : `/links/${props.workspaceUuid}/store`;
    const method = editingLink.value ? 'PUT' : 'POST';

    try {
        isSubmitting.value = true;

        const response = await fetch(endpoint, {
            method,
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-XSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const payloadError = await response.json().catch(() => null);
            throw new Error(payloadError?.message ?? 'Não foi possível salvar o link.');
        }

        toast.success(editingLink.value ? 'Link atualizado com sucesso.' : 'Link criado com sucesso.');
        router.reload({ only: ['categories', 'links'] });

        if (editingLink.value) {
            closeEditDialog();
            return;
        }

        closeCreateDialog();
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Erro ao salvar o link.');
    } finally {
        isSubmitting.value = false;
    }
}

async function confirmDeleteLink() {
    if (!deletingLink.value) {
        return;
    }

    try {
        isSubmitting.value = true;

        const response = await fetch(`/links/${deletingLink.value.uuid}/destroy`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-XSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            const payloadError = await response.json().catch(() => null);
            throw new Error(payloadError?.message ?? 'Não foi possível excluir o link.');
        }

        toast.success('Link removido com sucesso.');
        router.reload({ only: ['categories', 'links'] });
        closeDeleteDialog();
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Erro ao excluir o link.');
    } finally {
        isSubmitting.value = false;
    }
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
                @click="openCreateDialog()"
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
                    class="flex items-center justify-between gap-3 rounded-sm border border-border bg-background p-3"
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

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-sm border border-border bg-background text-foreground transition hover:bg-muted"
                            @click="openEditLink(linkItem)"
                            aria-label="Editar link"
                        >
                            <Pencil class="size-3.5" />
                        </button>

                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-sm border border-border bg-background text-foreground transition hover:bg-muted"
                            @click="openDeleteLink(linkItem)"
                            aria-label="Excluir link"
                        >
                            <Trash2 class="size-3.5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <Dialog :open="isCreateDialogOpen" @update:open="isCreateDialogOpen = $event">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Novo link
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Adicione um novo link ao workspace e organize por categoria.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-5 py-2">
                <div class="space-y-2">
                    <label for="link-name" class="text-sm font-medium text-foreground">
                        Nome
                    </label>
                    <input
                        id="link-name"
                        v-model="form.name"
                        type="text"
                        maxlength="255"
                        placeholder="Ex: Dribbble, Notion, Figma"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="link-url" class="text-sm font-medium text-foreground">
                        URL
                    </label>
                    <input
                        id="link-url"
                        v-model="form.url"
                        type="url"
                        placeholder="https://example.com"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="link-category" class="text-sm font-medium text-foreground">
                        Categoria
                    </label>
                    <select
                        id="link-category"
                        v-model="form.category_id"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    >
                        <option :value="null">Sem categoria</option>
                        <option
                            v-for="category in categories"
                            :key="category.uuid"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
            </div>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeCreateDialog()"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </Button>
                <Button
                    type="button"
                    @click="submitLink()"
                    :disabled="isSubmitting"
                >
                    {{ isSubmitting ? 'Salvando...' : 'Salvar link' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <Dialog :open="isEditDialogOpen" @update:open="isEditDialogOpen = $event">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Editar link
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Atualize as informações do link selecionado.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-5 py-2">
                <div class="space-y-2">
                    <label for="edit-link-name" class="text-sm font-medium text-foreground">
                        Nome
                    </label>
                    <input
                        id="edit-link-name"
                        v-model="form.name"
                        type="text"
                        maxlength="255"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="edit-link-url" class="text-sm font-medium text-foreground">
                        URL
                    </label>
                    <input
                        id="edit-link-url"
                        v-model="form.url"
                        type="url"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="edit-link-category" class="text-sm font-medium text-foreground">
                        Categoria
                    </label>
                    <select
                        id="edit-link-category"
                        v-model="form.category_id"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    >
                        <option :value="null">Sem categoria</option>
                        <option
                            v-for="category in categories"
                            :key="category.uuid"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
            </div>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeEditDialog()"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </Button>
                <Button
                    type="button"
                    @click="submitLink()"
                    :disabled="isSubmitting"
                >
                    {{ isSubmitting ? 'Salvando...' : 'Salvar alterações' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <Dialog :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Excluir link
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Tem certeza que deseja remover este link? Essa ação não pode ser desfeita.
                </DialogDescription>
            </DialogHeader>

            <div class="py-2 text-sm text-foreground">
                {{ deletingLink?.name || deletingLink?.url || 'Este link' }}
            </div>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeDeleteDialog()"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </Button>
                <Button
                    type="button"
                    variant="destructive"
                    @click="confirmDeleteLink()"
                    :disabled="isSubmitting"
                >
                    {{ isSubmitting ? 'Excluindo...' : 'Excluir' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
