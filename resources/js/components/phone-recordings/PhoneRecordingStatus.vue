<script setup lang="ts">
import { PhoneRecording } from '@/types';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '../ui/tooltip';
import { ref } from 'vue';
import { CloudDownload, FileCheck, Phone } from 'lucide-vue-next';

interface Props {
    status: PhoneRecording["status"];
}

const props = defineProps<Props>();
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