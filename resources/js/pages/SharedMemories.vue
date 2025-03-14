<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type SharedMemory, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import RecordingPlayer from '@/components/RecordingPlayer.vue';

import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { storageUrl } from '@/composables/storageUrl';
import MediaViewer from '@/components/shared-memories/MediaViewer.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shared Memories',
        href: '/shared-memories',
    },
];

interface Props {
    sharedMemories: SharedMemory[];
}

const props = defineProps<Props>();

const sharedVolume = ref(1);

watch(() => sharedVolume.value, (volume) => {
    localStorage.setItem('volume', volume.toString());
});

onMounted(function () {
    sharedVolume.value = parseFloat(localStorage.getItem('volume') ?? '1');
    const channel = Echo.private('App.Models.SharedMemory');
    channel.listen('.SharedMemoryCreated', (e: { model: SharedMemory }) => {
        props.sharedMemories.unshift(e.model);
    });
    channel.listen('.SharedMemoryUpdated', (e: { model: SharedMemory }) => {
        const i = props.sharedMemories.findIndex((m) => m.id === e.model.id);
        props.sharedMemories[i] = e.model;
    });
    channel.listen('.SharedMemoryDeleted', (e: { model: SharedMemory }) => {
        props.sharedMemories.splice(props.sharedMemories.findIndex((m) => m.id === e.model.id), 1);
    });
});

onBeforeUnmount(function () {
    Echo.private('App.Models.SharedMemory').stopListeningToAll();
});
</script>

<template>

    <Head title="Shared Memories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Written Message</TableHead>
                        <TableHead>Left At</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="memory in props.sharedMemories" :key="memory.id">
                        <TableCell class="font-medium">
                            {{ memory.name }}
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ memory.message }}
                        </TableCell>
                        <TableCell>{{ new Date(memory.created_at).toLocaleString() }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex flex-row gap-3 justify-end">
                                <template v-if="memory.has_voice_message">
                                    <RecordingPlayer
                                        :download-url="`shared-memories/${memory.id}/download-voice-message`"
                                        :url="storageUrl(`voice_messages/${memory.id}.${memory.voice_message_extension}`)"
                                        :volume="sharedVolume" @update-volume="sharedVolume = $event" />
                                </template>
                                <template v-if="memory.attachments.length > 0">
                                    <MediaViewer :sharedMemory="memory" />
                                </template>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
