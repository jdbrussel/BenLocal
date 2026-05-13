<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationGroup(): ?string
    {
        return __('ui.admin.nav.users_trust');
    }

    public static function getLabel(): ?string
    {
        return __('ui.admin.resources.user');
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('ui.auth.name') ?? 'Name')
                    ->required(),
                TextInput::make('email')
                    ->label(__('ui.auth.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('avatar')
                    ->label(__('ui.profile.avatar') ?? 'Avatar'),
                Select::make('preferred_language')
                    ->label(__('ui.language'))
                    ->options(config('benlocal.available_languages')),
                Select::make('preferred_theme')
                    ->label(__('ui.theme'))
                    ->options([
                        'light' => __('ui.light'),
                        'dark' => __('ui.dark'),
                        'system' => __('ui.system'),
                    ]),
                TextInput::make('country')
                    ->label(__('ui.profile.country') ?? 'Country'),
                TextInput::make('city')
                    ->label(__('ui.profile.city') ?? 'City'),
                Select::make('residence_region_id')
                    ->label(__('ui.admin.resources.region'))
                    ->relationship('residenceRegion', 'name->' . config('benlocal.default_language')),
                Select::make('residence_area_id')
                    ->label(__('ui.admin.resources.area'))
                    ->relationship('residenceArea', 'name->' . config('benlocal.default_language')),
                Select::make('residence_place_id')
                    ->label(__('ui.admin.resources.place'))
                    ->relationship('residencePlace', 'name->' . config('benlocal.default_language')),
                Select::make('community_id')
                    ->label(__('ui.admin.resources.community'))
                    ->relationship('community', 'name->' . config('benlocal.default_language')),
                TextInput::make('trust_penalty_score')
                    ->label(__('ui.admin.trust_penalty') ?? 'Trust Penalty')
                    ->numeric()
                    ->default(0),
                TextInput::make('suspended_until')
                    ->label(__('ui.admin.suspended_until') ?? 'Suspended Until')
                    ->type('datetime-local'),
                Toggle::make('is_shadowbanned')
                    ->label(__('ui.admin.is_shadowbanned') ?? 'Shadowbanned'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('ui.profile.avatar') ?? 'Avatar')
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('ui.auth.name') ?? 'Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('ui.auth.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('community.name.' . config('benlocal.default_language'))
                    ->label(__('ui.admin.resources.community')),
                TextColumn::make('trust_penalty_score')
                    ->label(__('ui.admin.trust_penalty') ?? 'Penalty')
                    ->sortable(),
                TextColumn::make('suspended_until')
                    ->label(__('ui.admin.suspended_until') ?? 'Suspended')
                    ->dateTime(),
                IconColumn::make('is_shadowbanned')
                    ->label(__('ui.admin.is_shadowbanned') ?? 'Shadowbanned')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('ui.common.created_at') ?? 'Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('community')
                    ->label(__('ui.admin.resources.community'))
                    ->relationship('community', 'name->' . config('benlocal.default_language')),
                TernaryFilter::make('is_shadowbanned')
                    ->label(__('ui.admin.is_shadowbanned') ?? 'Shadowbanned'),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
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



