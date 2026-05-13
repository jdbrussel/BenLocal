<?php

namespace App\Filament\Admin\Resources\SpotClaims\Tables;

use App\Enums\ClaimStatus;
use App\Enums\UserRole;
use App\Mail\ClaimApproved;
use App\Mail\ClaimRejected;
use App\Mail\MoreInfoNeeded;
use App\Models\SpotOwnerRole;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SpotClaimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('business_name')
                    ->searchable(),
                TextColumn::make('contact_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ClaimStatus::class),
            ])
            ->actions([
                EditAction::make(),
                Action::make('approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status === ClaimStatus::PENDING)
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => ClaimStatus::APPROVED,
                                'approved_at' => now(),
                                'approved_by' => auth()->id(),
                            ]);

                            $spot = $record->spot;
                            $spot->update([
                                'is_claimed' => true,
                                'claimed_at' => now(),
                            ]);

                            $user = $record->user;
                            if ($user) {
                                $user->update(['role' => UserRole::OWNER]);

                                SpotOwnerRole::firstOrCreate([
                                    'spot_id' => $spot->id,
                                    'user_id' => $user->id,
                                ], [
                                    'role' => 'owner',
                                ]);
                            }

                            // Send ClaimApproved email
                            Mail::to($record->email)->send(new ClaimApproved($record));
                        });
                    })
                    ->requiresConfirmation(),
                Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => $record->status === ClaimStatus::PENDING)
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => ClaimStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        // Send ClaimRejected email
                        Mail::to($record->email)->send(new ClaimRejected($record));
                    })
                    ->requiresConfirmation(),
                Action::make('request_info')
                    ->label('Request Info')
                    ->color('warning')
                    ->icon('heroicon-o-information-circle')
                    ->visible(fn ($record) => $record->status === ClaimStatus::PENDING)
                    ->form([
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Information Needed')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => ClaimStatus::MORE_INFO_NEEDED,
                        ]);

                        // Send MoreInfoNeeded email
                        Mail::to($record->email)->send(new MoreInfoNeeded($record, $data['notes']));
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
