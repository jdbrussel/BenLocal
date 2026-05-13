<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations;

use App\Filament\Admin\Resources\CampaignRecommendations\Pages\CreateCampaignRecommendation;
use App\Filament\Admin\Resources\CampaignRecommendations\Pages\EditCampaignRecommendation;
use App\Filament\Admin\Resources\CampaignRecommendations\Pages\ListCampaignRecommendations;
use App\Filament\Admin\Resources\CampaignRecommendations\Schemas\CampaignRecommendationForm;
use App\Filament\Admin\Resources\CampaignRecommendations\Tables\CampaignRecommendationsTable;
use App\Models\CampaignRecommendation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampaignRecommendationResource extends Resource
{
    protected static ?string $model = CampaignRecommendation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CampaignRecommendationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampaignRecommendationsTable::configure($table);
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
            'index' => ListCampaignRecommendations::route('/'),
            'create' => CreateCampaignRecommendation::route('/create'),
            'edit' => EditCampaignRecommendation::route('/{record}/edit'),
        ];
    }
}
