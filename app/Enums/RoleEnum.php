<?php

namespace App\Enums;

enum RoleEnum: string
{
    case APPLICATION_ADMIN     = 'application_admin';
    case INVESTIGATION_ADMIN   = 'investigation_admin';
    case USER_MANAGER          = 'user_manager';
    case APPLICATION_MANAGER   = 'application_manager';
    case INVESTIGATION_MANAGER = 'investigation_manager';
    case SYSTEM_ADMINISTRATOR  = 'system_administrator';
    case SUPERVISOR            = 'supervisor';
}
