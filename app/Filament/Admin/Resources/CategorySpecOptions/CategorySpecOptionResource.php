<?php

namespace App\Filament\Admin\Resources\CategorySpecOptions;

use App\Filament\Admin\Resources\CategorySpecOptions\Pages\CreateCategorySpecOption;
use App\Filament\Admin\Resources\CategorySpecOptions\Pages\EditCategorySpecOption;
use App\Filament\Admin\Resources\CategorySpecOptions\Pages\ListCategorySpecOptions;
use App\Filament\Support\TranslatableField;
use App\Models\CategorySpecOption;
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

class CategorySpecOptionResource extends Resource
{
    protected static ?string $model = CategorySpecOption::class;

    protected static string|UnitEnum|null $navigationGroup = 'Food & Drinks';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('spec_type')
                    ->options([
                        'rating' => 'Rating Spec',
                        'filter' => 'Filter Spec',
                    ])
                    ->required()
                    ->reactive(),
                Select::make('spec_id')
                    ->label('Specification')
                    ->options(function (callable $get) {
                        $type = $get('spec_type');
                        try {
                            if ($type === 'rating') {
                                return \App\Models\CategoryRatingSpec::all()->pluck('key', 'id');
                            }
                            if ($type === 'filter') {
                                return \App\Models\CategoryFilterSpec::all()->pluck('key', 'id');
                            }
                        } catch (\Throwable $e) {
                            return [];
                        }
                        return [];
                    })
                    ->required()
                    ->searchable(),
                TextInput::make('value')
                    ->required(),
                TranslatableField::make('label')
                    ->columnSpanFull(),
                TranslatableField::make('description', 'Description', 'textarea')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spec_type'),
                TextColumn::make('spec_id')
                    ->label('Spec ID'),
                TextColumn::make('value'),
                TextColumn::make('label.' . config('benlocal.default_language'))
                    ->label('Label')
                    ->searchable(),
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
            'index' => ListCategorySpecOptions::route('/'),
            'create' => CreateCategorySpecOption::route('/create'),
            'edit' => EditCategorySpecOption::route('/{record}/edit'),
        ];
    }
}



