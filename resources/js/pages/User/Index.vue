<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, destroy, edit, index } from '@/routes/users';

interface ManagedUser {
    uuid: string;
    name: string;
    email: string;
    type: string;
    created_at: string;
}

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Usuários', href: index() }],
    },
});

const page = usePage<{
    users: ManagedUser[];
    auth: { user: { uuid: string } };
}>();

const users = computed(() => page.props.users);
const currentUserUuid = computed(() => page.props.auth.user.uuid);

const userToDelete = ref<ManagedUser | null>(null);
const isDeleteModalOpen = ref(false);
const deleteError = ref<string | null>(null);

function askToDelete(user: ManagedUser) {
    userToDelete.value = user;
    deleteError.value = null;
    isDeleteModalOpen.value = true;
}

function handleDelete() {
    if (!userToDelete.value) {
        return;
    }

    router.delete(destroy.url(userToDelete.value.uuid), {
        preserveScroll: true,
        onError: (errors) => {
            deleteError.value = errors.user ?? 'Não foi possível excluir o usuário.';
        },
        onFinish: () => {
            isDeleteModalOpen.value = false;
            userToDelete.value = null;
        },
    });
}

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}
</script>

<template>
    <Head title="Usuários" />

    <div class="mt-4 px-4">
        <div class="mb-4 flex items-center justify-between">
            <Heading title="Usuários" class="mb-0" />

            <Button as-child class="gap-2">
                <Link :href="create.url()">
                    <Plus class="size-4" />
                    Criar usuário
                </Link>
            </Button>
        </div>

        <p v-if="deleteError" class="mb-4 text-sm text-destructive">{{ deleteError }}</p>

        <p v-if="users.length === 0" class="text-sm text-muted-foreground">
            Nenhum usuário encontrado.
        </p>

        <div v-else class="overflow-x-auto rounded-lg border">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-muted/50 text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nome</th>
                        <th class="px-4 py-3 font-medium">E-mail</th>
                        <th class="px-4 py-3 font-medium">Tipo</th>
                        <th class="px-4 py-3 font-medium">Criado em</th>
                        <th class="px-4 py-3 text-right font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.uuid" class="border-b last:border-0">
                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ user.email }}</td>
                        <td class="px-4 py-3">
                            <Badge :variant="user.type === 'admin' ? 'default' : 'secondary'">
                                {{ user.type === 'admin' ? 'Administrador' : 'Regular' }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ formatDate(user.created_at) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                <Button as-child variant="ghost" size="icon" :aria-label="`Editar ${user.name}`">
                                    <Link :href="edit.url(user.uuid)">
                                        <Pencil class="size-4" />
                                    </Link>
                                </Button>
                                <Button
                                    v-if="user.uuid !== currentUserUuid"
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    class="text-destructive"
                                    :aria-label="`Excluir ${user.name}`"
                                    @click="askToDelete(user)"
                                >
                                    <Trash2 class="size-4" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <ConfirmDialog
            v-model:is-open="isDeleteModalOpen"
            title="Excluir usuário"
            :message="`Tem certeza que deseja excluir o usuário '${userToDelete?.name ?? ''}'? Essa ação não poderá ser desfeita.`"
            confirm-text="Excluir"
            cancel-text="Cancelar"
            @confirm="handleDelete"
        />
    </div>
</template>
