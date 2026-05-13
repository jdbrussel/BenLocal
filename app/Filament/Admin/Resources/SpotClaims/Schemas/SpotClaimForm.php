<?php

namespace App\Filament\Admin\Resources\SpotClaims\Schemas;

use App\Enums\ClaimStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class SpotClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('spot_id')
                    ->relationship('spot', 'name')
                    ->required()
                    ->searchable(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
                TextInput::make('business_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                FileUpload::make('proof_document_path')
                    ->label('Proof Document')
                    ->directory('claim-proofs'),
                Textarea::make('proof_notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(ClaimStatus::class)
                    ->required(),
                Textarea::make('rejection_reason')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
