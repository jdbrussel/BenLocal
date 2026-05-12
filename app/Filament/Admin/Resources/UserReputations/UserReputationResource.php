<?php

namespace App\Filament\Admin\Resources\UserReputations;

use App\Filament\Admin\Resources\UserReputations\Pages\CreateUserReputation;
use App\Filament\Admin\Resources\UserReputations\Pages\EditUserReputation;
use App\Filament\Admin\Resources\UserReputations\Pages\ListUserReputations;
use App\Models\UserReputation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use UnitEnum;
use BackedEnum;

class UserReputationResource extends Resource
{
    protected static ?string $model = UserReputation::class;

    protected static string|UnitEnum|null $navigationGroup = 'Users & Trust';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('region_id')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                Select::make('sector_id')
                    ->relationship('sector', 'name->' . config('benlocal.default_language')),
                Select::make('category_id')
                    ->relationship('category', 'name->' . config('benlocal.default_language')),
                Select::make('community_id')
                    ->relationship('community', 'name->' . config('benlocal.default_language')),
                TextInput::make('local_status'),
                TextInput::make('recommendation_count')
                    ->numeric()
                    ->default(0),
                TextInput::make('confirmed_recommendation_score')
                    ->numeric()
                    ->default(0),
                TextInput::make('review_score')
                    ->numeric()
                    ->default(0),
                TextInput::make('follower_count')
                    ->numeric()
                    ->default(0),
                TextInput::make('hidden_gem_score')
                    ->numeric()
                    ->default(0),
                TextInput::make('trust_score')
                    ->numeric()
                    ->default(0),
                TextInput::make('rank')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('region.name.' . config('benlocal.default_language'))
                    ->label('Region'),
                TextColumn::make('community.name.' . config('benlocal.default_language'))
                    ->label('Community'),
                TextColumn::make('local_status'),
                TextColumn::make('trust_score')
                    ->sortable(),
                TextColumn::make('rank')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->relationship('region', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('community')
                    ->relationship('community', 'name->' . config('benlocal.default_language')),
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
            'index' => ListUserReputations::route('/'),
            'create' => CreateUserReputation::route('/create'),
            'edit' => EditUserReputation::route('/{record}/edit'),
        ];
    }
}



