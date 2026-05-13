<?php

namespace App\Filament\Admin\Resources\ModerationActions;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\ModerationActions\Pages\CreateModerationAction;
use App\Filament\Admin\Resources\ModerationActions\Pages\EditModerationAction;
use App\Filament\Admin\Resources\ModerationActions\Pages\ListModerationActions;
use App\Models\ModerationAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

use UnitEnum;
use BackedEnum;

class ModerationActionResource extends Resource
{
    protected static ?string $model = ModerationAction::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Moderation';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('moderator_id')
                    ->relationship('moderator', 'name')
                    ->required(),
                TextInput::make('target_type')
                    ->required(),
                TextInput::make('target_id')
                    ->required()
                    ->numeric(),
                TextInput::make('action')
                    ->required(),
                TextInput::make('reason')
                    ->required(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('moderator.name')
                    ->label('Moderator')
                    ->sortable(),
                TextColumn::make('target_type'),
                TextColumn::make('action'),
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
            'index' => ListModerationActions::route('/'),
            'create' => CreateModerationAction::route('/create'),
            'edit' => EditModerationAction::route('/{record}/edit'),
        ];
    }
}



