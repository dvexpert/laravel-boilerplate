<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Models\{Permission, User};
use Illuminate\Foundation\Inspiring;

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

        /** @var User $user */
        $user = $request->user();

        return [
            'name'  => config('app.name'),
            'quote' => ['message' => trim((string) $message), 'author' => trim((string) $author)],
            'auth'  => [
                'user' => $user,
                'can'  => $user?->getPermissionsViaRoles()
                    ->mapWithKeys(fn (Permission $permission) => [$permission['name'] => $user->can($permission['name'])])
                    ->all(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash'       => $request->session()->all(),
            ...parent::share($request),
        ];
    }
}
