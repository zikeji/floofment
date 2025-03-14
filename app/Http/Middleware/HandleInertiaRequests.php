<?php

namespace App\Http\Middleware;

use App\Models\PhoneRecording;
use App\Models\SharedMemory;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            ...(auth()->hasUser() ? [
                'counts' => [
                    'phoneRecordings' => PhoneRecording::count(),
                    'sharedMemories' => SharedMemory::count(),
                ],
            ] : []),
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
                'storage_base_url' => Str::rtrim(Storage::disk('s3')->url('fake-path'), 'fake-path'),
            ],
        ];
    }
}
