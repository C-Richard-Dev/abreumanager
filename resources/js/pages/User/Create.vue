<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import UserForm from '@/components/UserForm.vue';
import { index, store } from '@/routes/users';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Usuários', href: index() },
            { title: 'Criar', href: '#' },
        ],
    },
});

defineProps<{
    types: string[];
}>();

const form = useForm({
    name: '',
    email: '',
    type: 'regular',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Criar usuário" />

    <div class="mt-4 px-4">
        <Heading title="Criar usuário" class="mb-6" />

        <UserForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
            :types="types"
            submit-label="Criar"
            @submit="submit"
        />
    </div>
</template>
