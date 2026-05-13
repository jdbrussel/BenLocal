<?php

namespace App\Filament\Admin\Resources\Reviews;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\Reviews\Pages\CreateReview;
use App\Filament\Admin\Resources\Reviews\Pages\EditReview;
use App\Filament\Admin\Resources\Reviews\Pages\ListReviews;
use App\Filament\Support\TranslatableField;
use App\Models\Review;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Recommendations & Reviews';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('spot_id')
                    ->relationship('spot', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->required()
                    ->searchable(),
                Select::make('recommendation_id')
                    ->relationship('recommendation', 'id'),
                TextInput::make('overall_rating')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),
                KeyValue::make('rating_values'),
                TranslatableField::make('review_text', 'Review Text', 'textarea'),
                TextInput::make('visited_at')
                    ->type('date'),
                Toggle::make('confirms_recommendation'),
                Toggle::make('verified_visit'),
                Select::make('moderation_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'flagged' => 'Flagged',
                        'hidden' => 'Hidden',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name')
                    ->label('Spot')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('overall_rating')
                    ->sortable(),
                IconColumn::make('confirms_recommendation')
                    ->boolean(),
                IconColumn::make('verified_visit')
                    ->boolean(),
                TextColumn::make('moderation_status'),
                TextColumn::make('flagged_count')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('moderation_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'hidden' => 'Hidden',
                        'suspicious' => 'Suspicious',
                    ]),
                TernaryFilter::make('verified_visit'),
                TernaryFilter::make('confirms_recommendation'),
                Filter::make('rating_range')
                    ->form([
                        TextInput::make('min_rating')->numeric()->minValue(1)->maxValue(5),
                        TextInput::make('max_rating')->numeric()->minValue(1)->maxValue(5),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min_rating'], fn ($q) => $q->where('overall_rating', '>=', $data['min_rating']))
                            ->when($data['max_rating'], fn ($q) => $q->where('overall_rating', '<=', $data['max_rating']));
                    })
            ])
            ->actions([
                Action::make('approve')
                    ->action(fn (Review $record) => $record->update(['moderation_status' => 'approved']))
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Review $record) => $record->moderation_status !== 'approved'),
                Action::make('reject')
                    ->action(fn (Review $record) => $record->update(['moderation_status' => 'rejected']))
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (Review $record) => !in_array($record->moderation_status, ['rejected', 'hidden'])),
                Action::make('mark_suspicious')
                    ->action(fn (Review $record) => $record->update(['moderation_status' => 'suspicious']))
                    ->color('warning')
                    ->icon('heroicon-o-exclamation-triangle'),
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}



