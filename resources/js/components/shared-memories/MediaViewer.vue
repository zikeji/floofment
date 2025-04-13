<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { storageUrl } from '@/composables/storageUrl';
import { SharedMemory } from '@/types';
import { Images } from 'lucide-vue-next';
import Galleria from 'primevue/galleria';
import { ref } from 'vue';

interface Props {
    sharedMemory: SharedMemory;
}

const visible = ref(false);

defineProps<Props>();
</script>

<template>
    <Button variant="outline" size="icon" @click="visible = !visible">
        <Images />
    </Button>
    <Galleria
        v-model:visible="visible"
        :value="sharedMemory.attachments"
        container-style="max-width: 90vw"
        full-screen
        circular
        show-item-navigators
        show-item-navigators-on-hover
        :show-thumbnails="false"
    >
        <template #item="slotProps">
            <img :src="storageUrl(`memory_attachments/${slotProps.item.id}.${slotProps.item.extension}`)" style="width: 100%" />
        </template>
    </Galleria>
</template>
