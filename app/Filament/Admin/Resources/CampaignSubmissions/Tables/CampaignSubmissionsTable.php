<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Actions\Action;
use App\Services\AI\AIEnrichmentService;
use App\Services\Campaign\CampaignRecommendationService;
use Filament\Notifications\Notification;

class CampaignSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.name')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('submitted_name')
                    ->searchable(),
                TextColumn::make('matchedSpot.name')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('ai_enrich')
                    ->label('AI Enrich')
                    ->icon('heroicon-o-sparkles')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record, AIEnrichmentService $service) {
                        $service->enrichCampaignSubmission($record);
                        Notification::make()
                            ->title('AI Enrichment Started')
                            ->success()
                            ->send();
                    }),
                Action::make('convert_to_recommendation')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->user_id && ($record->matched_spot_id || $record->created_spot_id))
                    ->action(function ($record, CampaignRecommendationService $service) {
                        $service->createFromSubmission($record);
                        $record->update(['status' => 'converted']);
                        Notification::make()
                            ->title('Converted to Recommendation')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
