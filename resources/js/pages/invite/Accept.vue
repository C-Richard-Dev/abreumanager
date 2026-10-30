<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { UserPlus } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { accept, decline } from '@/routes/invite';

defineOptions({
    layout: {
        title: 'Convite para workspace',
        description: 'Você foi convidado para participar de um workspace.',
    },
});

defineProps<{
    token: string;
    workspaceName: string;
    senderName: string;
}>();
</script>

<template>
    <Head title="Convite para workspace" />

    <div class="space-y-6">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-muted text-foreground">
            <UserPlus class="size-5" aria-hidden="true" />
        </div>

        <p class="text-center text-sm text-muted-foreground">
            <span class="font-medium text-foreground">{{ senderName }}</span>
            convidou você para ser membro do workspace
            <span class="font-medium text-foreground">{{ workspaceName }}</span>.
        </p>

        <Form
            v-bind="accept.form(token)"
            v-slot="{ errors, processing }"
            class="space-y-3"
        >
            <InputError :message="errors.token" />

            <Button type="submit" class="w-full" :disabled="processing">
                <Spinner v-if="processing" />
                Aceitar convite
            </Button>

        </Form>

        <Form
            v-bind="decline.form(token)"
            v-slot="{ processing }"
        >
            <Button type="submit" variant="secondary" class="w-full" :disabled="processing">
                Recusar convite
            </Button>
        </Form>
    </div>
</template>
