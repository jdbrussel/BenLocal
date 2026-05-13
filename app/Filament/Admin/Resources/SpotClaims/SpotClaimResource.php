<?php

namespace App\Filament\Admin\Resources\SpotClaims;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\SpotClaims\Pages\CreateSpotClaim;
use App\Filament\Admin\Resources\SpotClaims\Pages\EditSpotClaim;
use App\Filament\Admin\Resources\SpotClaims\Pages\ListSpotClaims;
use App\Models\SpotClaim;
use Filament\Forms\Components\FileUpload;
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

class SpotClaimResource extends Resource
{
    protected static ?string $model = SpotClaim::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Business Claims';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('spot_id')
                    ->relationship('spot', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('business_name')
                    ->required(),
                TextInput::make('contact_name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone'),
                TextInput::make('website')
                    ->url(),
                FileUpload::make('proof_document_path'),
                TextInput::make('proof_notes'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'more_info' => 'More Info Requested',
                    ])
                    ->default('pending')
                    ->required(),
                Select::make('approved_by')
                    ->relationship('approver', 'name'),
                TextInput::make('approved_at')
                    ->type('datetime-local'),
                TextInput::make('rejection_reason'),
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
                TextColumn::make('business_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('status'),
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
            'index' => ListSpotClaims::route('/'),
            'create' => CreateSpotClaim::route('/create'),
            'edit' => EditSpotClaim::route('/{record}/edit'),
        ];
    }
}



