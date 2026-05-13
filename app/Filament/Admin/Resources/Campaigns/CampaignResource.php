<?php

namespace App\Filament\Admin\Resources\Campaigns;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\Campaigns\Pages\CreateCampaign;
use App\Filament\Admin\Resources\Campaigns\Pages\EditCampaign;
use App\Filament\Admin\Resources\Campaigns\Pages\ListCampaigns;
use App\Filament\Support\TranslatableField;
use App\Models\Campaign;
use Filament\Forms\Components\KeyValue;
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

use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Campaigns';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                TranslatableField::make('name')
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TranslatableField::make('description', 'Description', 'textarea')
                    ->columnSpanFull(),
                TextInput::make('source_type'),
                TextInput::make('source_name'),
                TextInput::make('source_url')
                    ->url(),
                Select::make('region_id')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                Select::make('sector_id')
                    ->relationship('sector', 'name->' . config('benlocal.default_language')),
                Select::make('category_id')
                    ->relationship('category', 'name->' . config('benlocal.default_language')),
                Select::make('default_community_id')
                    ->relationship('defaultCommunity', 'name->' . config('benlocal.default_language')),
                TranslatableField::make('landing_title'),
                TranslatableField::make('landing_intro', 'Landing Intro', 'textarea'),
                TranslatableField::make('cta_text'),
                TranslatableField::make('success_message'),
                KeyValue::make('publication_context'),
                TextInput::make('starts_at')
                    ->type('datetime-local'),
                TextInput::make('ends_at')
                    ->type('datetime-local'),
                Toggle::make('requires_login'),
                Toggle::make('requires_facebook_login'),
                Toggle::make('auto_create_spots'),
                Toggle::make('ai_enrichment_enabled'),
                Toggle::make('notify_spot_by_email'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name.' . config('benlocal.default_language'))
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('source_type'),
                TextColumn::make('region.name.' . config('benlocal.default_language'))
                    ->label('Region'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('starts_at')
                    ->dateTime(),
                TextColumn::make('ends_at')
                    ->dateTime(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
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
            'index' => ListCampaigns::route('/'),
            'create' => CreateCampaign::route('/create'),
            'edit' => EditCampaign::route('/{record}/edit'),
        ];
    }
}



