<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'

interface User {
    id: number
    name: string
    email: string
    workspaces: unknown[]
}

const page = usePage<{
    auth: {
        user: User
    }
    workspaces_count: number
}>()

const user = computed(() => page.props.auth.user)
const workspacesCount = computed(() => page.props.workspaces_count)
const workspaceName = computed(() => 'Workspace ' + (workspacesCount.value + 1))

const form = useForm({
    name: workspaceName.value,
})

const submit = () => form.post('/workspaces')
</script>

<template>
    <div class="mt-4 ml-4 mr-4">
        <h1 class="text-2xl font-bold">Criar workspace</h1>

        <h6 
          v-if="workspacesCount === 0"
          class="text-sm mb-4"
            >
            Detectamos que você ainda não possui nenhum workspace.
        </h6>

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
                Criar
            </Button>
        </form>
    </div>
</template>