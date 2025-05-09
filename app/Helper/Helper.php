<?php

namespace App\Helper;

use App\Models\User;

class Helper
{
    /**
     * To check if logged in user is allowed to see debug info
     * Like telescope, pulse and logs
     */
    public static function isDebugAuthUser(?User $user = null): bool
    {
        /** @var User $user */
        $user ??= auth('web')->user();
        $allowed = false;
        if ($user) {
            $allowed = str_contains((string) $user->email, '@teqbuddies.com') || // phpcs:ignore
                str_contains((string) $user->email, 'teqbuddies@gmail.com');
        }

        return (app()->isLocal() || app()->runningUnitTests()) || $allowed;
    }
}
