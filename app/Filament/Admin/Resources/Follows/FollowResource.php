<?php

namespace App\Filament\Admin\Resources\Follows;

use App\Filament\Admin\Resources\Follows\Pages\CreateFollow;
use App\Filament\Admin\Resources\Follows\Pages\EditFollow;
use App\Filament\Admin\Resources\Follows\Pages\ListFollows;
use App\Models\Follow;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class FollowResource extends Resource
{
    protected static ?string $model = Follow::class;

    protected static string|UnitEnum|null $navigationGroup = 'Users & Trust';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('follower_id')
                    ->relationship('follower', 'name')
                    ->required(),
                Select::make('followed_id')
                    ->relationship('followed', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('follower.name')
                    ->label('Follower')
                    ->sortable(),
                TextColumn::make('followed.name')
                    ->label('Followed')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListFollows::route('/'),
            'create' => CreateFollow::route('/create'),
            'edit' => EditFollow::route('/{record}/edit'),
        ];
    }
}



