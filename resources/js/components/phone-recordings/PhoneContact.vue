<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { PhoneRecording } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';

interface Props {
    recording: PhoneRecording;
}

const props = defineProps<Props>();

const form = useForm({
    label: props.recording.label,
});

const open = ref(false);

const submit = () => {
    form.patch(route('phone-recordings.update', props.recording.sid), {
        onSuccess: () => {
            open.value = false;
        },
    });
};

watch(
    () => open.value,
    (isOpen) => {
        if (!isOpen) {
            nextTick(() => {
                form.reset();
            });
        }
    },
);
</script>

<template>
    <Popover :open="open" @update:open="open = $event">
        <PopoverTrigger @click="open = !open">{{ recording.label }}</PopoverTrigger>
        <PopoverContent>
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <div class="grid grid-cols-3 items-center gap-4">
                        <Label for="nickname">Nickname</Label>
                        <Input id="nickname" type="text" v-model="form.label" @keyup.enter="submit" class="col-span-2 h-8" />
                    </div>
                </div>
                <div class="space-y-2">
                    <InputError v-if="form.errors.label" :message="form.errors.label" />
                    <p v-else class="text-sm text-muted-foreground">Apply a persistent nickname to this phone number.</p>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
