<?php

namespace App\Filament\Admin\Resources\Faqs\Pages;

use App\Filament\Admin\Resources\Faqs\FaqResource;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;
}
