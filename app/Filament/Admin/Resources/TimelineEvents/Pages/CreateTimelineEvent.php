<?php

namespace App\Filament\Admin\Resources\TimelineEvents\Pages;

use App\Filament\Admin\Resources\TimelineEvents\TimelineEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimelineEvent extends CreateRecord
{
    protected static string $resource = TimelineEventResource::class;
}
