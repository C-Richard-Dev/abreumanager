<script setup lang="ts">
import { ref } from 'vue';
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

const props = defineProps<{
    workspaceUuid: string | null;
}>();

const isOpen = defineModel<boolean>('isOpen');

const emit = defineEmits<{
    (event: 'created', category: { id: number; uuid: string; name: string }): void;
}>();

const form = ref({
    name: '',
    description: '',
});

const isSubmitting = ref(false);

function resetForm() {
    form.value = {
        name: '',
        description: '',
    };
}

function closeDialog() {
    isOpen.value = false;
    resetForm();
}

function getCsrfToken(): string {
    const cookie = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='));

    if (!cookie) {
        return '';
    }

    return decodeURIComponent(cookie.split('=')[1]);
}

async function submit() {
    if (!props.workspaceUuid) {
        toast.error('Workspace não foi identificado.');
        return;
    }

    const name = form.value.name.trim();

    if (!name) {
        toast.error('Informe o nome da categoria.');
        return;
    }

    try {
        isSubmitting.value = true;

        const csrfToken = getCsrfToken();
        const response = await fetch(`/categories/${props.workspaceUuid}/store`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-XSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                name,
                description: form.value.description.trim() || null,
            }),
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => null);
            throw new Error(payload?.message ?? 'Não foi possível criar a categoria.');
        }

        const createdCategory = await response.json();

        toast.success('Categoria criada com sucesso.');
        emit('created', createdCategory);
        closeDialog();
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Erro ao criar categoria.');
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Dialog
        :open="isOpen"
        @update:open="isOpen = $event"
    >
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="text-left text-xl font-semibold text-foreground">
                    Nova categoria
                </DialogTitle>
                <DialogDescription class="text-left text-sm text-muted-foreground">
                    Crie uma categoria para organizar os links deste workspace.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-5 py-2">
                <div class="space-y-2">
                    <label for="category-name" class="text-sm font-medium text-foreground">
                        Nome
                    </label>
                    <input
                        id="category-name"
                        v-model="form.name"
                        type="text"
                        maxlength="255"
                        placeholder="Ex: Design, Recursos, Notícias"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>

                <div class="space-y-2">
                    <label for="category-description" class="text-sm font-medium text-foreground">
                        Descrição
                    </label>
                    <textarea
                        id="category-description"
                        v-model="form.description"
                        rows="4"
                        placeholder="Opcional: descreva o propósito dessa categoria"
                        class="w-full resize-none rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground outline-none transition focus:border-foreground"
                    />
                </div>
            </div>

            <DialogFooter class="gap-2 sm:justify-end">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeDialog()"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </Button>
                <Button
                    type="button"
                    @click="submit()"
                    :disabled="isSubmitting"
                >
                    {{ isSubmitting ? 'Criando...' : 'Criar categoria' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
