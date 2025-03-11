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

usePoll(5000);

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
