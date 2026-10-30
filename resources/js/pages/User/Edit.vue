<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import UserForm from '@/components/UserForm.vue';
import { index, update } from '@/routes/users';

interface EditableUser {
    uuid: string;
    name: string;
    email: string;
    type: string;
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Usuários', href: index() },
            { title: 'Editar', href: '#' },
        ],
    },
});

const props = defineProps<{
    user: EditableUser;
    types: string[];
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    type: props.user.type,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(update.url(props.user.uuid));
};
</script>

<template>
    <Head title="Editar usuário" />

    <div class="mt-4 px-4">
        <Heading title="Editar usuário" class="mb-6" />

        <UserForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
            :types="types"
            submit-label="Salvar"
            password-optional
            @submit="submit"
        />
    </div>
</template>
