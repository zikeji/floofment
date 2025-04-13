import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    badgeNumber?: Ref<number, number>;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & {
        location: string;
        storage_base_url: string;
    };
    counts?: {
        phoneRecordings: number;
        sharedMemories: number;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface PhoneRecording {
    sid: string;
    called: string;
    from: string;
    label: string;
    status: 'started' | 'recorded' | 'available';
    created_at: string;
    updated_at: string;
}

export interface SharedMemory {
    id: string;
    name: string;
    message: string | null;
    has_voice_message: bool;
    voice_message_extension: string | null;
    attachments: SharedMemoryAttachment[];
    ip_address: string;
    user_agent: string;
    created_at: string;
    updated_at: string;
}

export interface SharedMemoryAttachment {
    id: string;
    name: string;
    mimeType: string;
    extension: string;
    size: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
