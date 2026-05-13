<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions;

use App\Filament\Admin\Resources\CampaignSubmissions\Pages\CreateCampaignSubmission;
use App\Filament\Admin\Resources\CampaignSubmissions\Pages\EditCampaignSubmission;
use App\Filament\Admin\Resources\CampaignSubmissions\Pages\ListCampaignSubmissions;
use App\Filament\Admin\Resources\CampaignSubmissions\Schemas\CampaignSubmissionForm;
use App\Filament\Admin\Resources\CampaignSubmissions\Tables\CampaignSubmissionsTable;
use App\Models\CampaignSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampaignSubmissionResource extends Resource
{
    protected static ?string $model = CampaignSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CampaignSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampaignSubmissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaignSubmissions::route('/'),
            'create' => CreateCampaignSubmission::route('/create'),
            'edit' => EditCampaignSubmission::route('/{record}/edit'),
        ];
    }
}
