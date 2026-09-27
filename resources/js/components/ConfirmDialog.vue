```vue
<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

import { Button } from '@/components/ui/button';

type Props = {
    title?: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
};

withDefaults(defineProps<Props>(), {
    title: 'Confirmar ação',
    confirmText: 'Confirmar',
    cancelText: 'Cancelar',
});

const isOpen = defineModel<boolean>('isOpen');

const emit = defineEmits<{
    confirm: [];
}>();

const handleConfirm = () => {
    emit('confirm');
};
</script>

<template>
    <Dialog
        :open="isOpen"
        @update:open="isOpen = $event"
    >
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>
                    {{ title }}
                </DialogTitle>

                <DialogDescription>
                    {{ message }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-2">
                <Button
                    type="button"
                    variant="outline"
                    @click="isOpen = false"
                >
                    {{ cancelText }}
                </Button>

                <Button
                    type="button"
                    variant="destructive"
                    @click="handleConfirm"
                >
                    {{ confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
```
