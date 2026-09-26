<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import { store } from '@/routes/workspaces'

interface Workspace {
    id: number
    name: string
    uuid: string
}

const page = usePage<{
    workspace: Workspace
}>()

const workspace = computed(() => page.props.workspace)

const form = useForm({
    name: workspace.value.name,
})

const submit = () => {
    form.post(store.url())
}
</script>

<template>
    <div class="mt-4 ml-4 mr-4">
        <h1 class="text-2xl font-bold">Editar workspace</h1>

        <form @submit.prevent="submit">
            <Input
                v-model="form.name"
                placeholder="Nome do workspace"
                class="mb-4"
            />

            <p v-if="form.errors.name">
                {{ form.errors.name }}
            </p>

            <Button
                type="submit"
                :disabled="form.processing"
            >
                Editar
            </Button>
        </form>
    </div>
</template>