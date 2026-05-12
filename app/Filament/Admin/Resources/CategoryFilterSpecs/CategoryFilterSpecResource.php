<?php

namespace App\Filament\Admin\Resources\CategoryFilterSpecs;

use App\Filament\Admin\Resources\CategoryFilterSpecs\Pages\CreateCategoryFilterSpec;
use App\Filament\Admin\Resources\CategoryFilterSpecs\Pages\EditCategoryFilterSpec;
use App\Filament\Admin\Resources\CategoryFilterSpecs\Pages\ListCategoryFilterSpecs;
use App\Filament\Support\TranslatableField;
use App\Models\CategoryFilterSpec;
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

class CategoryFilterSpecResource extends Resource
{
    protected static ?string $model = CategoryFilterSpec::class;

    protected static string|UnitEnum|null $navigationGroup = 'Food & Drinks';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

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
                TranslatableField::make('unit')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_required')
                    ->default(false),
                Toggle::make('is_filterable')
                    ->default(true),
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
                IconColumn::make('is_filterable')
                    ->label('Filterable')
                    ->boolean(),
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
            'index' => ListCategoryFilterSpecs::route('/'),
            'create' => CreateCategoryFilterSpec::route('/create'),
            'edit' => EditCategoryFilterSpec::route('/{record}/edit'),
        ];
    }
}



