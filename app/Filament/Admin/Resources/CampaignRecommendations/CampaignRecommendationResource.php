<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\CampaignRecommendations\Pages\CreateCampaignRecommendation;
use App\Filament\Admin\Resources\CampaignRecommendations\Pages\EditCampaignRecommendation;
use App\Filament\Admin\Resources\CampaignRecommendations\Pages\ListCampaignRecommendations;
use App\Models\CampaignRecommendation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;


class CampaignRecommendationResource extends Resource
{
    protected static ?string $model = CampaignRecommendation::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Campaigns';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-star';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                Select::make('campaign_id')
                    ->relationship('campaign', 'name->' . config('benlocal.default_language'))
                    ->required(),
                Select::make('submission_id')
                    ->relationship('submission', 'id'),
                Select::make('recommendation_id')
                    ->relationship('recommendation', 'id'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Select::make('spot_id')
                    ->relationship('spot', 'name->' . config('benlocal.default_language')),
                Toggle::make('selected_for_publication'),
                TextInput::make('publication_status'),
                TextInput::make('internal_notes'),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.name.' . config('benlocal.default_language'))
                    ->label('Campaign')
                    ->sortable(),
                TextColumn::make('spot.name.' . config('benlocal.default_language'))
                    ->label('Spot'),
                TextColumn::make('user.name')
                    ->label('User'),
                IconColumn::make('selected_for_publication')
                    ->boolean(),
                TextColumn::make('publication_status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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



