<?php

namespace App\Enums;

enum SpotLifecycleStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case FLAGGED = 'flagged';
    case DELETED = 'deleted';
}
