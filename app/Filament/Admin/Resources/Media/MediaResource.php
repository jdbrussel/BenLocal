<?php

namespace App\Filament\Admin\Resources\Media;

use App\Filament\Admin\Resources\Media\Pages\CreateMedia;
use App\Filament\Admin\Resources\Media\Pages\EditMedia;
use App\Filament\Admin\Resources\Media\Pages\ListMedia;
use App\Filament\Support\TranslatableField;
use App\Enums\ModerationStatus;
use App\Models\Media;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|UnitEnum|null $navigationGroup = 'Media';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('model_type')
                    ->required(),
                TextInput::make('model_id')
                    ->required()
                    ->numeric(),
                TextInput::make('collection'),
                FileUpload::make('file_path')
                    ->required(),
                TextInput::make('mime_type'),
                TextInput::make('size')
                    ->numeric(),
                TextInput::make('width')
                    ->numeric(),
                TextInput::make('height')
                    ->numeric(),
                TranslatableField::make('alt_text'),
                Select::make('uploaded_by')
                    ->relationship('user', 'name'),
                Select::make('moderation_status')
                    ->options(ModerationStatus::class)
                    ->default(ModerationStatus::PENDING),
                Toggle::make('is_primary'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview'),
                TextColumn::make('collection'),
                TextColumn::make('model_type'),
                TextColumn::make('user.name')
                    ->label('Uploaded By'),
                TextColumn::make('moderation_status'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('moderation_status')
                    ->options(ModerationStatus::class),
                TernaryFilter::make('is_primary'),
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
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}



