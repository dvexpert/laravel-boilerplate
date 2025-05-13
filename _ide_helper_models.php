<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    use Illuminate\Support\Carbon;
    use Spatie\Permission\Models\Permission;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * @property int $id
     * @property string $name
     * @property string $guard_name
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property-read Collection<int, Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read Collection<int, User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
     *
     * @mixin \Eloquent
     */
    #[\AllowDynamicProperties]
    class IdeHelperRole {}
}

namespace App\Models{
    use Illuminate\Support\Carbon;
    use Spatie\Permission\Models\Permission;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;

    /**
     * @property string $id
     * @property int $auto_id
     * @property string $first_name
     * @property string $last_name
     * @property string $email
     * @property Carbon|null $email_verified_at
     * @property string $password
     * @property string $status
     * @property string|null $remember_token
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property-read mixed $name
     * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read Collection<int, Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read Collection<int, Role> $roles
     * @property-read int|null $roles_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAutoId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
     *
     * @mixin \Eloquent
     */
    #[\AllowDynamicProperties]
    class IdeHelperUser {}
}
