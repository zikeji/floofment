<script setup lang="ts">
import { useTemplateRef, watch } from 'vue';
import { useMediaControls } from '@vueuse/core';
import { Button } from '@/components/ui/button';
import { DownloadCloud } from 'lucide-vue-next';

interface Props {
    url: string;
    downloadUrl: string;
    volume: number;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    updateVolume: [value: number]
}>();

const audio = useTemplateRef('audio');
const { volume: controlVolume } = useMediaControls(audio, {
    src: props.url,
});

watch(() => controlVolume.value, (volume) => {
    emit('updateVolume', volume);
});

watch(() => props.volume, (volume) => {
    controlVolume.value = volume;
});
</script>

<template>
    <div class="flex flex-row gap-3 justify-end">
        <audio ref="audio" controls />
        <Button as-child variant="outline" size="icon">
            <a :href="downloadUrl">
                <DownloadCloud />
            </a>
        </Button>
    </div>
</template>