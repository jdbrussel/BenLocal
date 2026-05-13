<?php

namespace App\Filament\Admin\Resources\Spots;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\Spots\Pages\CreateSpot;
use App\Filament\Admin\Resources\Spots\Pages\EditSpot;
use App\Filament\Admin\Resources\Spots\Pages\ListSpots;
use App\Filament\Support\TranslatableField;
use App\Models\Spot;
use App\Services\AI\AIEnrichmentService;
use App\Services\AI\AITranslationService;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
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

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Spots';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                Tabs::make('Spot Details')
                    ->tabs([
                        Tab::make('Basic info')
                            ->schema([
                                TranslatableField::make('name'),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TranslatableField::make('description', 'Description', 'textarea'),
                                TextInput::make('original_language')
                                    ->default('en'),
                                Select::make('sector_id')
                                    ->relationship('sector', 'name->' . config('benlocal.default_language'))
                                    ->required(),
                                Select::make('category_id')
                                    ->relationship('category', 'name->' . config('benlocal.default_language'))
                                    ->required(),
                            ]),
                        Tab::make('Location')
                            ->schema([
                                Select::make('region_id')
                                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                                Select::make('area_id')
                                    ->relationship('area', 'name->' . config('benlocal.default_language')),
                                Select::make('place_id')
                                    ->relationship('place', 'name->' . config('benlocal.default_language')),
                                KeyValue::make('address'),
                                TextInput::make('latitude')
                                    ->numeric(),
                                TextInput::make('longitude')
                                    ->numeric(),
                            ]),
                        Tab::make('Contact')
                            ->schema([
                                TextInput::make('phone')
                                    ->tel(),
                                TextInput::make('email')
                                    ->email(),
                                TextInput::make('website')
                                    ->url(),
                                KeyValue::make('opening_hours'),
                            ]),
                        Tab::make('Specs')
                            ->schema([
                                TextInput::make('price_level')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5),
                                KeyValue::make('spec_values')
                                    ->label('JSON Specs'),
                            ]),
                        Tab::make('Status')
                            ->schema([
                                Select::make('lifecycle_status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'flagged' => 'Flagged',
                                    ])
                                    ->required(),
                                Toggle::make('is_claimed'),
                                TextInput::make('claimed_at')
                                    ->type('datetime-local'),
                                Toggle::make('verified_business'),
                                TextInput::make('verified_at')
                                    ->type('datetime-local'),
                                Select::make('created_by')
                                    ->relationship('user', 'name'),
                            ]),
                        Tab::make('AI / Source')
                            ->schema([
                                Toggle::make('ai_enriched'),
                                KeyValue::make('ai_enrichment_data'),
                                TextInput::make('translated_at')
                                    ->type('datetime-local')
                                    ->disabled(),
                                TextInput::make('source'),
                                TextInput::make('source_reference'),
                            ]),
                    ])->columnSpanFull(),
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
                TextColumn::make('category.name.' . config('benlocal.default_language'))
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('region.name.' . config('benlocal.default_language'))
                    ->label('Region')
                    ->sortable(),
                TextColumn::make('area.name.' . config('benlocal.default_language'))
                    ->label('Area')
                    ->sortable(),
                TextColumn::make('lifecycle_status'),
                IconColumn::make('is_claimed')
                    ->label('Claimed')
                    ->boolean(),
                IconColumn::make('verified_business')
                    ->label('Verified')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('area')
                    ->relationship('area', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('category')
                    ->relationship('category', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('lifecycle_status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'flagged' => 'Flagged',
                    ]),
                TernaryFilter::make('is_claimed'),
                TernaryFilter::make('verified_business'),
            ])
            ->actions([
                EditAction::make(),
                \Filament\Tables\Actions\Action::make('translateMissing')
                    ->label('Translate Missing')
                    ->icon('heroicon-o-language')
                    ->action(function (Spot $record, AITranslationService $service) {
                        foreach ($record->translatable as $field) {
                            $service->translateModelField($record, $field, ['nl', 'en', 'es', 'de', 'fr']);
                        }
                    })
                    ->requiresConfirmation(),
                \Filament\Tables\Actions\Action::make('enrichSpot')
                    ->label('AI Enrich')
                    ->icon('heroicon-o-sparkles')
                    ->action(fn (Spot $record, AIEnrichmentService $service) => $service->enrichSpot($record))
                    ->requiresConfirmation(),
                \Filament\Tables\Actions\Action::make('applyEnrichment')
                    ->label('Approve AI')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Spot $record) => $record->ai_enriched && $record->ai_enrichment_data)
                    ->action(fn (Spot $record, AIEnrichmentService $service) => $service->applyEnrichment($record))
                    ->requiresConfirmation(),
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
            'index' => ListSpots::route('/'),
            'create' => CreateSpot::route('/create'),
            'edit' => EditSpot::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}



