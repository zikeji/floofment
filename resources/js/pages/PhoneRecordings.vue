<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { PhoneRecording, type BreadcrumbItem } from '@/types';
import { Head, usePoll } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import PhoneRecordingStatus from '@/components/phone-recordings/PhoneRecordingStatus.vue';
import RecordingPlayer from '@/components/phone-recordings/RecordingPlayer.vue';
import { onBeforeUnmount, onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Phone Recordings',
        href: '/phone-recordings',
    },
];

interface Props {
    recordings: PhoneRecording[];
}

const props = defineProps<Props>();

onMounted(function() {
    const channel = Echo.private('App.Models.PhoneRecording');
    channel.listen('.PhoneRecordingCreated', (e: { model: PhoneRecording }) => {
        props.recordings.unshift(e.model);
    });
    channel.listen('.PhoneRecordingUpdated', (e: { model: PhoneRecording }) => {
        const i = props.recordings.findIndex((m) => m.sid === e.model.sid);
        props.recordings[i] = e.model;
    });
    channel.listen('.PhoneRecordingDeleted', (e: { model: PhoneRecording }) => {
        props.recordings.splice(props.recordings.findIndex((m) => m.sid === e.model.sid), 1);
    });
});

onBeforeUnmount(function() {
    Echo.private('App.Models.PhoneRecording').stopListeningToAll();
});
</script>

<template>

    <Head title="Phone Recordings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">From</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Left At</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="recording in props.recordings" :key="recording.sid">
                        <TableCell class="font-medium">{{ recording.from }}</TableCell>
                        <TableCell>
                            <PhoneRecordingStatus :status="recording.status" />
                        </TableCell>
                        <TableCell>{{ new Date(recording.created_at).toLocaleString() }}</TableCell>
                        <TableCell class="text-right">
                            <template v-if="recording.status === 'available'">
                                <RecordingPlayer :recording="recording" />
                            </template>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
