<script setup lang="ts">
import { PhoneRecording } from '@/types';
import { CloudDownload, FileCheck, Phone } from 'lucide-vue-next';
import { ref } from 'vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '../ui/tooltip';

interface Props {
    status: PhoneRecording['status'];
}

defineProps<Props>();
const icons = ref({ started: Phone, recorded: CloudDownload, available: FileCheck });
const description = ref({
    started: 'This voicemail is in progress.',
    recorded: 'They finished recording and we are waiting on the recording.',
    available: 'This is ready to listen to!',
});
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <component :is="icons[status]" />
            </TooltipTrigger>
            <TooltipContent>
                <p v-text="description[status]"></p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
