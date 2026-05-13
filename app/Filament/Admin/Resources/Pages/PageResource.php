<?php

namespace App\Filament\Admin\Resources\Pages;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\Pages\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\Pages\ListPages;
use App\Filament\Support\TranslatableField;
use App\Models\Page;
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

use UnitEnum;
use BackedEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static \UnitEnum|string|null $navigationGroup = 'CMS & Legal';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TranslatableField::make('title'),
                TranslatableField::make('intro', 'Intro', 'textarea'),
                TranslatableField::make('content', 'Content', 'rich'),
                TranslatableField::make('seo_title'),
                TranslatableField::make('seo_description', 'SEO Description', 'textarea'),
                Toggle::make('is_system_page'),
                TextInput::make('published_at')
                    ->type('datetime-local'),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title.' . config('benlocal.default_language'))
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_system_page')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Page $record) => $record->is_system_page),
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}



