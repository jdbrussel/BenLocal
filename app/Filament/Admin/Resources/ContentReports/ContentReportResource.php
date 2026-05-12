<?php

namespace App\Filament\Admin\Resources\ContentReports;

use App\Filament\Admin\Resources\ContentReports\Pages\CreateContentReport;
use App\Filament\Admin\Resources\ContentReports\Pages\EditContentReport;
use App\Filament\Admin\Resources\ContentReports\Pages\ListContentReports;
use App\Models\ContentReport;
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
use UnitEnum;
use BackedEnum;

class ContentReportResource extends Resource
{
    protected static ?string $model = ContentReport::class;

    protected static string|UnitEnum|null $navigationGroup = 'Moderation';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reporter_user_id')
                    ->relationship('reporter', 'name')
                    ->required(),
                TextInput::make('reportable_type')
                    ->required(),
                TextInput::make('reportable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reason')
                    ->required(),
                TextInput::make('notes'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'under_review' => 'Under Review',
                        'resolved' => 'Resolved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
                Select::make('moderator_id')
                    ->relationship('moderator', 'name'),
                TextInput::make('resolution_notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reason')
                    ->searchable(),
                TextColumn::make('status'),
                TextColumn::make('reporter.name')
                    ->label('Reporter'),
                TextColumn::make('reportable_type'),
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
            'index' => ListContentReports::route('/'),
            'create' => CreateContentReport::route('/create'),
            'edit' => EditContentReport::route('/{record}/edit'),
        ];
    }
}



