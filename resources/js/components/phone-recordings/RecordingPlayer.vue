<script setup lang="ts">
import { computed, useTemplateRef } from 'vue';
import { useMediaControls } from '@vueuse/core';
import Button from '../ui/button/Button.vue';
import { DownloadCloud, Pause, Play } from 'lucide-vue-next';
import Slider from '../ui/slider/Slider.vue';
import { PhoneRecording } from '@/types';

interface Props {
    recording: PhoneRecording;
}

const props = defineProps<Props>();

const audio = useTemplateRef('audio');
const { playing, currentTime, duration, volume } = useMediaControls(audio, {
    src: props.recording.recording_url,
});

const currentTimeSeek = computed({
    get() {
        return [currentTime.value];
    },
    set(value: number[]) {
        currentTime.value = value[0];
    }
});

const currentTimeHuman = computed(() => `${Math.round(currentTime.value / 60)}:${(Math.round(currentTime.value % 60)).toString().padStart(2, '0')}`);
const durationHuman = computed(() => `${Math.round(duration.value / 60)}:${(Math.round(duration.value % 60)).toString().padStart(2, '0')}`);

</script>

<template>
    <audio ref="audio" />
    <div class="flex flex-row gap-3">
        <div class="flex w-full place-content-center flex-col">
            <Slider v-model="currentTimeSeek" :max="duration" :step="0.01" />
            <p class="text-muted-foreground text-right">{{ currentTimeHuman }} / {{ durationHuman }}</p>
        </div>
        <Button variant="outline" size="icon" @click="playing = !playing">
            <Pause v-if="playing" />
            <Play v-else />
        </Button>
        <Button as-child variant="outline" size="icon">
            <a :href="`phone-recordings/${recording.sid}/download`">
                <DownloadCloud />
            </a>
        </Button>
    </div>
</template>