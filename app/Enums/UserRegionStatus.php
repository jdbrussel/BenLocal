<?php

namespace App\Enums;

enum UserRegionStatus: string
{
    case TOURIST = 'tourist';
    case VISITOR = 'visitor';
    case REGULAR_VISITOR = 'regular_visitor';
    case LOCAL = 'local';
    case VERIFIED_LOCAL = 'verified_local';
}
