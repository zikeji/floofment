<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import FileUpload from 'primevue/fileupload';
import { CircleX } from 'lucide-vue-next';
import { ref } from 'vue';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

const isRecording = ref(false);
const audioURL = ref('');
let mediaRecorder: MediaRecorder;
let audioChunks: Blob[] = [];

const form = useForm<{
    name: string;
    message: string;
    voiceMessage: Blob | null;
    files: File[];
}>({
    name: '',
    message: '',
    voiceMessage: null,
    files: [],
});

const startRecording = async () => {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    mediaRecorder.start();
    isRecording.value = true;

    mediaRecorder.ondataavailable = (event) => {
        audioChunks.push(event.data);
    };

    mediaRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        audioURL.value = URL.createObjectURL(audioBlob);
        audioChunks = [];
        isRecording.value = false;
        form.voiceMessage = audioBlob;
    };
};

const stopRecording = () => {
    mediaRecorder.stop();
};

function submit() {
    form.post('/memory', {
        preserveScroll: 'errors',
    });
}
</script>

<template>

    <Head title="Share a Memory"></Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="page.props.auth.user" :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                    Log in
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div class="w-full lg:w-2/3 p-6 space-y-6 mx-auto">
                    <div class="space-y-2">
                        <h1 class="text-2xl font-semibold tracking-tight">Share Your Memory</h1>
                        <p class="text-sm">
                            Upload a photo, record a message, or leave a note for the happy couple.
                        </p>
                    </div>
                    <form class="space-y-4" @submit.prevent="submit">
                        <div class="grid w-full gap-1.5">
                            <Label for="name">Your Name</Label>
                            <Input id="name" v-model="form.name" type="text" placeholder="Almanzo Wilder" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="grid w-full gap-1.5">
                            <Label for="message">Your Message</Label>
                            <Textarea id="message" v-model="form.message" placeholder="Write your wishes here." />
                            <InputError :message="form.errors.message" />
                        </div>
                        <div class="grid w-full gap-1.5">
                            <Label class="text-sm font-medium">Voice Message</Label>
                            <Button type="button"
                                variant="secondary"
                                class="inline-flex items-center justify-center rounded-md border border-gray-200 px-4 py-2 text-sm font-medium dark:border-gray-800"
                                @click="isRecording ? stopRecording() : startRecording()">
                                {{ isRecording ? '🛑 Stop Recording' : '🎤 Record Audio Message' }}
                            </Button>
                            <div>
                                <audio class="w-full" v-if="audioURL" :src="audioURL" controls></audio>
                            </div>
                            <InputError :message="form.errors.voiceMessage" />
                        </div>
                        <div class="grid w-full gap-1.5">
                            <Label class="text-sm font-medium">Photo Memory</Label>
                            <div class="flex w-full items-center justify-center">
                                <FileUpload name="media[]" :multiple="true" :fileLimit="10"
                                    @select="form.files = $event.files" :customUpload="true"
                                    :pt="{ root: { class: 'w-full' } }" :showUploadButton="false" :showCancelButton="false"
                                    accept="image/*" :maxFileSize="25 * 1024 * 1024">
                                    <template #empty>
                                        <span>Drag and drop files to here to upload.</span>
                                    </template>
                                    <template #content="{ files, uploadedFiles, removeFileCallback, messages }">
                                        <div class="flex flex-col gap-8 pt-4">
                                            <div v-for="message of messages" :key="message"
                                                :class="{ 'mb-8': !files.length && !uploadedFiles.length }"
                                                severity="error">
                                                <InputError :message="message" />
                                            </div>

                                            <div v-if="files.length > 0">
                                                <div class="flex flex-wrap gap-4">
                                                    <div v-for="(file, index) of files"
                                                        :key="file.name + file.type + file.size"
                                                        class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                                                        <div>
                                                            <img role="presentation" :alt="file.name"
                                                                :src="(file as any).objectURL" width="100" height="50" />
                                                        </div>
                                                        <span
                                                            class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden">{{
                                                            file.name }}</span>
                                                        <Button @click.prevent="removeFileCallback"
                                                            variant="destructive" severity="danger">
                                                            <CircleX />
                                                        </Button>
                                                        <InputError
                                                            :message="(form as any).errors?.[`files.${index}`]" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </FileUpload>
                                <InputError :message="form.errors.files" />
                            </div>
                        </div>
                        <Button type="submit" class="w-full">
                            Share Memory
                        </Button>
                    </form>
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
