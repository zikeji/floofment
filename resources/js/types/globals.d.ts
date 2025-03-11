import type { route as routeFn } from 'ziggy-js';
import type Pusher from 'pusher-js';
import type Echo from 'laravel-echo';

declare global {
    const route: typeof routeFn;
    const Pusher: Pusher;
    const Echo: Echo<{broadcaster: 'reverb'}>;
}
