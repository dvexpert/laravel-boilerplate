<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_READ   = 'user.read';
    case USER_CREATE = 'user.create';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';
}
