<?php

namespace App\Filament\Owner\Resources\MySpots\Tables;

use App\Models\Community;
use App\Models\SpotCommunityProfile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

class MySpotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('verified_business')
                    ->label('Verified')
                    ->boolean(),
                TextColumn::make('claimed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('suggest_profile')
                    ->label('Suggest Community')
                    ->icon('heroicon-o-user-group')
                    ->color('info')
                    ->form([
                        Select::make('community_id')
                            ->label('Community')
                            ->options(Community::all()->pluck('name', 'id'))
                            ->required(),
                        Textarea::make('motivation')
                            ->label('Why is this spot relevant for this community?')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // In Phase 11, we store it as a community profile with low confidence or a special source
                        SpotCommunityProfile::create([
                            'spot_id' => $record->id,
                            'community_id' => $data['community_id'],
                            'confidence_score' => 0.1, // Low confidence since it's a suggestion
                            'source' => 'owner_suggestion',
                        ]);

                        // TODO: Log suggestion or notify admin
                        Log::info("Owner suggested community profile for spot {$record->id}");
                    })
                    ->requiresConfirmation(),
                Action::make('report_incorrect')
                    ->label('Report Error')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->form([
                        Textarea::make('description')
                            ->label('What is incorrect?')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // TODO: Implement reporting system or send email to support
                        Log::warning("Owner reported incorrect information for spot {$record->id}: " . $data['description']);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // Owners should not bulk delete their spots
            ]);
    }
}
