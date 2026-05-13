<?php

namespace App\Enums;

enum ClaimStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case MORE_INFO_NEEDED = 'more_info_needed';
}
