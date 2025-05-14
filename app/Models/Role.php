<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @mixin IdeHelperRole
 */
class Role extends SpatieRole
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
            get: fn () => str($this->name)->replace('_', ' ')->title()->toString(),
        );
    }
}
