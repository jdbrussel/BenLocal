<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\CampaignSubmissions\Pages\CreateCampaignSubmission;
use App\Filament\Admin\Resources\CampaignSubmissions\Pages\EditCampaignSubmission;
use App\Filament\Admin\Resources\CampaignSubmissions\Pages\ListCampaignSubmissions;
use App\Models\CampaignSubmission;
use Filament\Forms\Components\KeyValue;
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

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;

class CampaignSubmissionResource extends Resource
{
    protected static ?string $model = CampaignSubmission::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Campaigns';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-plus';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                Select::make('campaign_id')
                    ->relationship('campaign', 'name->' . config('benlocal.default_language'))
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('guest_token'),
                TextInput::make('submitted_name')
                    ->required(),
                TextInput::make('submitted_notes'),
                TextInput::make('submitted_place_hint'),
                Select::make('matched_spot_id')
                    ->relationship('matchedSpot', 'name->' . config('benlocal.default_language')),
                Select::make('created_spot_id')
                    ->relationship('createdSpot', 'name->' . config('benlocal.default_language')),
                KeyValue::make('ai_result'),
                Toggle::make('user_confirmed_spot'),
                Toggle::make('wants_to_recommend'),
                Toggle::make('consent_to_contact'),
                Toggle::make('consent_to_publish_quote'),
                Toggle::make('consent_to_terms'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'duplicate' => 'Duplicate',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.name.' . config('benlocal.default_language'))
                    ->label('Campaign')
                    ->sortable(),
                TextColumn::make('submitted_name')
                    ->searchable(),
                TextColumn::make('matchedSpot.name.' . config('benlocal.default_language'))
                    ->label('Matched Spot'),
                TextColumn::make('status'),
                IconColumn::make('user_confirmed_spot')
                    ->boolean(),
                IconColumn::make('wants_to_recommend')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('campaign')
                    ->relationship('campaign', 'name->' . config('benlocal.default_language')),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'duplicate' => 'Duplicate',
                        'rejected' => 'Rejected',
                    ]),
                TernaryFilter::make('user_confirmed_spot'),
                TernaryFilter::make('wants_to_recommend'),
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
            'index' => ListCampaignSubmissions::route('/'),
            'create' => CreateCampaignSubmission::route('/create'),
            'edit' => EditCampaignSubmission::route('/{record}/edit'),
        ];
    }
}



