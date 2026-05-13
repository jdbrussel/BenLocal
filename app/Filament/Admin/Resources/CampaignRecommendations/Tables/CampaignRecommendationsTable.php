<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Actions\Action;
use App\Services\Campaign\CampaignSelectionService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;

class CampaignRecommendationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.name')
                    ->sortable(),
                TextColumn::make('spot.name')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                IconColumn::make('selected_for_publication')
                    ->boolean(),
                TextColumn::make('publication_status')
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
                Action::make('shortlist')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(fn ($record, CampaignSelectionService $service) => $service->shortlist($record)),
                Action::make('select_for_magazine')
                    ->icon('heroicon-o-book-open')
                    ->color('success')
                    ->action(fn ($record, CampaignSelectionService $service) => $service->selectForMagazine($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_csv')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records, CampaignSelectionService $service) {
                            // Simple export for selected records
                            $campaignId = $records->first()?->campaign_id;
                            if (!$campaignId) return;

                            $csv = $service->exportCampaignData($campaignId, 'csv');
                            return response()->streamDownload(fn () => print($csv), "campaign-export.csv");
                        })
                ]),
            ]);
    }
}
