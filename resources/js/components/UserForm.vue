<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/users';

export interface UserFormData {
    name: string;
    email: string;
    type: string;
    password: string;
    password_confirmation: string;
}

type FormErrors = Partial<Record<keyof UserFormData, string>>;

const props = defineProps<{
    form: UserFormData;
    errors: FormErrors;
    processing: boolean;
    types: string[];
    submitLabel: string;
    passwordOptional?: boolean;
}>();

defineEmits<{
    submit: [];
}>();

const typeLabels: Record<string, string> = {
    admin: 'Administrador',
    regular: 'Regular',
};
</script>

<template>
    <form class="grid max-w-xl gap-4" @submit.prevent="$emit('submit')">
        <div class="grid gap-2">
            <Label for="name">Nome</Label>
            <Input id="name" v-model="props.form.name" placeholder="Nome completo" required />
            <InputError :message="errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="email">E-mail</Label>
            <Input id="email" v-model="props.form.email" type="email" placeholder="usuario@exemplo.com" required />
            <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
            <Label for="type">Tipo de usuário</Label>
            <select
                id="type"
                v-model="props.form.type"
                class="border-input dark:bg-input/30 h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
            >
                <option v-for="type in types" :key="type" :value="type">
                    {{ typeLabels[type] ?? type }}
                </option>
            </select>
            <InputError :message="errors.type" />
        </div>

        <div class="grid gap-2">
            <Label for="password">Senha</Label>
            <Input
                id="password"
                v-model="props.form.password"
                type="password"
                autocomplete="new-password"
                :placeholder="passwordOptional ? 'Deixe em branco para manter a atual' : 'Digite a senha'"
                :required="!passwordOptional"
            />
            <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
            <Label for="password_confirmation">Confirmar senha</Label>
            <Input
                id="password_confirmation"
                v-model="props.form.password_confirmation"
                type="password"
                autocomplete="new-password"
                placeholder="Digite a senha novamente"
                :required="!passwordOptional"
            />
        </div>

        <div class="flex items-center gap-3">
            <Button type="submit" :disabled="processing">
                {{ submitLabel }}
            </Button>
            <Button as-child variant="ghost">
                <Link :href="index.url()">Cancelar</Link>
            </Button>
        </div>
    </form>
</template>
