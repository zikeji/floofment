import type Echo from 'laravel-echo';
import type Pusher from 'pusher-js';
import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    const Pusher: Pusher;
    const Echo: Echo<'reverb'>;
}
