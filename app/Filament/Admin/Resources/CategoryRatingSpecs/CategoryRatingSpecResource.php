<?php

namespace App\Filament\Admin\Resources\CategoryRatingSpecs;

use App\Filament\Admin\Resources\CategoryRatingSpecs\Pages\CreateCategoryRatingSpec;
use App\Filament\Admin\Resources\CategoryRatingSpecs\Pages\EditCategoryRatingSpec;
use App\Filament\Admin\Resources\CategoryRatingSpecs\Pages\ListCategoryRatingSpecs;
use App\Filament\Support\TranslatableField;
use App\Models\CategoryRatingSpec;
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
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class CategoryRatingSpecResource extends Resource
{
    protected static ?string $model = CategoryRatingSpec::class;

    protected static string|UnitEnum|null $navigationGroup = 'Food & Drinks';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name->' . config('benlocal.default_language'))
                    ->required()
                    ->searchable(),
                TextInput::make('key')
                    ->required(),
                TranslatableField::make('name')
                    ->columnSpanFull(),
                TranslatableField::make('description', 'Description', 'textarea')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('min_value')
                    ->numeric(),
                TextInput::make('max_value')
                    ->numeric(),
                TextInput::make('weight')
                    ->numeric()
                    ->default(1),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_required')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name.' . config('benlocal.default_language'))
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('name.' . config('benlocal.default_language'))
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('weight'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            'index' => ListCategoryRatingSpecs::route('/'),
            'create' => CreateCategoryRatingSpec::route('/create'),
            'edit' => EditCategoryRatingSpec::route('/{record}/edit'),
        ];
    }
}



