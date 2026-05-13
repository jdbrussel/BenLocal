<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SUPPORT = 'support';
    case OWNER = 'owner';
    case USER = 'user';
}
