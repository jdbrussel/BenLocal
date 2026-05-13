<?php

namespace App\Filament\Admin\Resources\Faqs;

use App\Filament\Admin\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Admin\Resources\Faqs\Pages\EditFaq;
use App\Filament\Admin\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Support\TranslatableField;
use App\Models\Faq;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static \UnitEnum|string|null $navigationGroup = 'CMS & Legal';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TranslatableField::make('question'),
                TranslatableField::make('answer', 'Answer', 'rich'),
                TextInput::make('category'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question.' . config('benlocal.default_language'))
                    ->label('Question')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->sortable(),
                IconColumn::make('is_active')
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
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}
