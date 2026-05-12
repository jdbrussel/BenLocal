<?php

namespace App\Filament\Admin\Resources\Recommendations;

use App\Filament\Admin\Resources\Recommendations\Pages\CreateRecommendation;
use App\Filament\Admin\Resources\Recommendations\Pages\EditRecommendation;
use App\Filament\Admin\Resources\Recommendations\Pages\ListRecommendations;
use App\Filament\Support\TranslatableField;
use App\Models\Recommendation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;

    protected static string|UnitEnum|null $navigationGroup = 'Recommendations & Reviews';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-hand-thumb-up';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('spot_id')
                    ->relationship('spot', 'name->' . config('benlocal.default_language'))
                    ->required()
                    ->searchable(),
                Select::make('region_id')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                Select::make('community_id')
                    ->relationship('community', 'name->' . config('benlocal.default_language')),
                TranslatableField::make('title'),
                TranslatableField::make('motivation', 'Motivation', 'textarea'),
                TextInput::make('original_language')
                    ->default('en'),
                TextInput::make('confidence_score')
                    ->numeric(),
                Toggle::make('hidden_gem_candidate'),
                Select::make('moderation_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'flagged' => 'Flagged',
                        'hidden' => 'Hidden',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name.' . config('benlocal.default_language'))
                    ->label('Spot')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('region.name.' . config('benlocal.default_language'))
                    ->label('Region'),
                IconColumn::make('hidden_gem_candidate')
                    ->boolean(),
                TextColumn::make('moderation_status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('moderation_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'flagged' => 'Flagged',
                        'hidden' => 'Hidden',
                    ]),
                TernaryFilter::make('hidden_gem_candidate'),
            ])
            ->actions([
                Action::make('approve')
                    ->action(fn (Recommendation $record) => $record->update(['moderation_status' => 'approved']))
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation(),
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
            'index' => ListRecommendations::route('/'),
            'create' => CreateRecommendation::route('/create'),
            'edit' => EditRecommendation::route('/{record}/edit'),
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



