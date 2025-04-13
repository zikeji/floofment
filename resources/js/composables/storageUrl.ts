import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

export function storageUrl(path: string): string {
    const page = usePage<SharedData>();
    return `${page.props.ziggy.storage_base_url}${path}`;
}
