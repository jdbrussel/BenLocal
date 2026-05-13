<?php

namespace App\Filament\Owner\Resources\Reviews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Details')
                    ->schema([
                        TextInput::make('user.name')
                            ->label('User')
                            ->disabled(),
                        TextInput::make('overall_rating')
                            ->label('Rating')
                            ->disabled(),
                        Textarea::make('review_text')
                            ->label('Review Text')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Your Response')
                    ->schema([
                        Textarea::make('owner_response')
                            ->label('Response')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        DateTimePicker::make('owner_responded_at')
                            ->label('Responded At')
                            ->default(now())
                            ->disabled(),
                    ]),
            ]);
    }
}
