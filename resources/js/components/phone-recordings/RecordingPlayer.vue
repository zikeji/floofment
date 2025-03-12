<script setup lang="ts">
import { useTemplateRef, watch } from 'vue';
import { useMediaControls } from '@vueuse/core';
import Button from '../ui/button/Button.vue';
import { DownloadCloud, Pause, Play } from 'lucide-vue-next';
import { PhoneRecording } from '@/types';

interface Props {
    recording: PhoneRecording;
    volume: number;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    updateVolume: [value: number]
}>();

const audio = useTemplateRef('audio');
const { volume: controlVolume } = useMediaControls(audio, {
    src: props.recording.recording_url,
});

watch(() => controlVolume.value, (volume) => {
    emit('updateVolume', volume);
});

watch(() => props.volume, (volume) => {
    controlVolume.value = volume;
});
</script>

<template>
    <div class="flex flex-row gap-3">
        <audio ref="audio" controls />
        <Button as-child variant="outline" size="icon">
            <a :href="`phone-recordings/${recording.sid}/download`">
                <DownloadCloud />
            </a>
        </Button>
    </div>
</template>