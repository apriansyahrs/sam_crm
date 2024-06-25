<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Region;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                DateTimePicker::make('email_verified_at'),
                Select::make('business_entity_id')
                    ->label('Business Entity')
                    ->options(BusinessEntity::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('division_id')
                    ->label('Division')
                    ->options(Division::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('region_id')
                    ->label('Region')
                    ->options(Region::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('cluster_id')
                    ->label('Cluster')
                    ->options(Cluster::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('cluster_id2')
                    ->label('Cluster Optional')
                    ->options(Cluster::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('tm_id')
                    ->label('TM')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('businessEntity.name'),
                TextColumn::make('division.name'),
                TextColumn::make('region.name'),
                TextColumn::make('cluster.name'),
                // TagsColumn::make('roles.name'),
            ])
            ->filters([
                Filter::make('region')
                    ->form([
                        Select::make('businessEntity')
                            ->label('Business Entity')
                            ->options(BusinessEntity::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                // Reset division and region when businessEntity is changed
                                $set('division', null);
                                $set('region', null);
                            }),

                        Select::make('division')
                            ->label('Division')
                            ->options(function (callable $get) {
                                $businessEntityId = $get('businessEntity');
                                if ($businessEntityId) {
                                    return Division::where('business_entity_id', $businessEntityId)->orderBy('name', 'asc')->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                // Reset region when division is changed
                                $set('region', null);
                            }),

                        Select::make('region')
                            ->label('Region')
                            ->searchable()
                            ->options(function (callable $get) {
                                $divisionId = $get('division');
                                if ($divisionId) {
                                    return Region::where('division_id', $divisionId)->orderBy('name', 'asc')->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->reactive(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['businessEntity']) {
                            $query->where('business_entity_id', $data['businessEntity']);
                        }
                        if ($data['division']) {
                            $query->where('division_id', $data['division']);
                        }
                        if ($data['region']) {
                            $query->where('region_id', $data['region']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Impersonate::make()
                    ->redirectTo(route('filament.admin.pages.dashboard')),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
