<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Position;
use App\Models\Region;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
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
                // Personal Information
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        Select::make('position_id')
                            ->label('Position')
                            ->required()
                            ->options(Position::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->columnSpan(6),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->columnSpan(6),
                    ])
                    ->columns(12) // Mengatur layout form dalam grid 12 kolom untuk lebih rapi dan terstruktur
                    ->columnSpanFull(),

                // Business Information
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('business_entity_id')
                            ->label('Business Entity')
                            ->options(BusinessEntity::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                // Reset dependent selects
                                $set('division_id', null);
                                $set('region_id', null);
                                $set('cluster_id', null);
                                $set('cluster_id2', null);
                            })
                            ->columnSpan(6),
                        Select::make('division_id')
                            ->label('Division')
                            ->options(function (callable $get) {
                                $businessEntityId = $get('business_entity_id');
                                if ($businessEntityId) {
                                    return Division::where('business_entity_id', $businessEntityId)
                                        ->orderBy('name', 'asc')
                                        ->pluck('name', 'id')->toArray();
                                }
                                return [];
                            })
                            ->reactive()
                            ->searchable()
                            ->columnSpan(6),
                        Select::make('region_id')
                            ->label('Region')
                            ->options(function (callable $get) {
                                $divisionId = $get('division_id');
                                if ($divisionId) {
                                    return Region::where('division_id', $divisionId)
                                        ->orderBy('name', 'asc')
                                        ->pluck('name', 'id')->toArray();
                                }
                                return [];
                            })
                            ->reactive()
                            ->searchable()
                            ->columnSpan(6),
                        Select::make('cluster_id')
                            ->label('Cluster')
                            ->options(function (callable $get) {
                                $regionId = $get('region_id');
                                if ($regionId) {
                                    return Cluster::where('region_id', $regionId)
                                        ->orderBy('name', 'asc')
                                        ->pluck('name', 'id')->toArray();
                                }
                                return [];
                            })
                            ->reactive()
                            ->searchable()
                            ->columnSpan(6),
                        Select::make('cluster_id2')
                            ->label('Cluster Optional')
                            ->options(function (callable $get) {
                                $regionId = $get('region_id');
                                if ($regionId) {
                                    return Cluster::where('region_id', $regionId)
                                        ->orderBy('name', 'asc')
                                        ->pluck('name', 'id')->toArray();
                                }
                                return [];
                            })
                            ->reactive()
                            ->searchable()
                            ->columnSpan(6),
                        Select::make('tm_id')
                            ->label('TM')
                            ->options(User::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->columnSpan(6),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),

                // Account Security
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull(),
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
                TextColumn::make('position.name'),
                TextColumn::make('businessEntity.name'),
                TextColumn::make('division.name'),
                TextColumn::make('region.name'),
                TextColumn::make('cluster.name'),
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
            ->defaultSort('name', 'asc')
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
