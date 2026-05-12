<?php

namespace App\Enums;

enum ReviewReactionType: string
{
    case AGREE = 'agree';
    case PARTLY = 'partly';
    case DISAGREE = 'disagree';
}
