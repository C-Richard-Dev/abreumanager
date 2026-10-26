<script setup lang="ts">
import { ref } from 'vue';
import { Form } from '@inertiajs/vue3';
import { Mail, UserPlus, Users } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { invite, users as workspaceUsers } from '@/routes/workspaces';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';

interface Member {
    uuid: string;
    name: string;
    email: string;
}

const props = defineProps<{
    workspaceUuid: string;
}>();

const members = ref<Member[]>([]);
const loading = ref(false);
const failed = ref(false);
const isInviting = ref(false);

async function loadMembers(open: boolean) {
    if (!open) {
        isInviting.value = false;

        return;
    }

    loading.value = true;
    failed.value = false;

    try {
        const response = await fetch(workspaceUsers.url(props.workspaceUuid), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(String(response.status));
        }

        members.value = await response.json();
    } catch {
        failed.value = true;
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <Sheet @update:open="loadMembers">
        <SheetTrigger as-child>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-sm border border-border bg-background px-4 py-2 text-sm font-medium leading-normal text-foreground transition hover:bg-muted"
            >
                <Users class="size-4" />
                Ver Membros
            </button>
        </SheetTrigger>

        <SheetContent side="right" class="w-full sm:max-w-sm">
            <SheetHeader>
                <SheetTitle>Membros ({{ members.length }})</SheetTitle>
                <SheetDescription>
                    Usuários com permissão para ver este workspace.
                </SheetDescription>
            </SheetHeader>

            <div class="px-4">
                <Button
                    v-if="!isInviting"
                    type="button"
                    class="w-full gap-2"
                    @click="isInviting = true"
                >
                    <UserPlus class="size-4" />
                    Convidar Membro
                </Button>

                <Form
                    v-else
                    v-bind="invite.form(workspaceUuid)"
                    reset-on-success
                    :options="{ preserveScroll: true }"
                    class="grid gap-3 rounded-md border border-border p-3"
                    v-slot="{ errors, processing, clearErrors }"
                    @success="isInviting = false"
                >
                    <div class="grid gap-2">
                        <Label for="invite-email">E-mail do convidado</Label>
                        <div class="relative">
                            <Mail class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                id="invite-email"
                                name="email"
                                type="email"
                                autofocus
                                required
                                placeholder="usuario@exemplo.com"
                                class="pl-9"
                            />
                        </div>
                        <InputError :message="errors.email" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button
                            type="button"
                            variant="secondary"
                            @click="
                                () => {
                                    clearErrors();
                                    isInviting = false;
                                }
                            "
                        >
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="processing">
                            {{ processing ? 'Enviando...' : 'Enviar convite' }}
                        </Button>
                    </div>
                </Form>
            </div>

            <div class="flex flex-col gap-2 px-4 pb-4 pt-4">
                <template v-if="loading">
                    <div v-for="i in 3" :key="i" class="h-14 animate-pulse rounded-md bg-muted" />
                </template>

                <p v-else-if="failed" class="text-sm text-destructive">
                    Não foi possível carregar os participantes.
                </p>

                <p v-else-if="members.length === 0" class="text-sm text-muted-foreground">
                    Nenhum participante encontrado.
                </p>

                <ul v-else class="flex flex-col gap-2">
                    <li
                        v-for="member in members"
                        :key="member.uuid"
                        class="rounded-md border border-border px-3 py-2"
                    >
                        <p class="text-sm font-medium text-foreground">{{ member.name }}</p>
                        <p class="text-xs text-muted-foreground">{{ member.email }}</p>
                    </li>
                </ul>
            </div>
        </SheetContent>
    </Sheet>
</template>
