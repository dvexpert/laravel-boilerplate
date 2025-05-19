<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends SpatiePermission implements \Spatie\Permission\Contracts\Permission
{
    protected $hidden = [
        'pivot',
    ];

    protected $appends = [
        'name_label',
    ];

    public function nameLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->name)->replace(['.', '_'], ' ')->title()->toString(),
        );
    }
}
