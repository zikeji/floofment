<script setup lang="ts">
import { PhoneRecording } from '@/types';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '../ui/tooltip';
import Icon from '@/components/Icon.vue';
import { ref } from 'vue';

interface Props {
    status: PhoneRecording["status"];
}

const props = defineProps<Props>();
const icons = ref({started: 'Phone', recorded: 'CloudDownload', available: 'FileCheck'});
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
                <Icon :name="icons[status]"/>
            </TooltipTrigger>
            <TooltipContent>
                <p v-text="description[status]"></p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>