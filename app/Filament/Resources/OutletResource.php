<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletResource\Pages;
use App\Filament\Resources\OutletResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Outlet;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutletResource extends Resource
{
    protected static ?string $model = Outlet::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Outlet Details')
                        ->schema([
                            TextInput::make('code')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('owner')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('telp')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('address')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('latlong')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                        ])
                        ->columns(12),
                    Step::make('Business Information')
                        ->schema([
                            Select::make('business_entity_id')
                                ->label('Business Entity')
                                ->options(BusinessEntity::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                                ->reactive()
                                ->searchable()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $set('division_id', null);
                                    $set('region_id', null);
                                    $set('cluster_id', null);
                                })
                                ->columnSpan(6),
                            Select::make('division_id')
                                ->label('Division')
                                ->options(function (callable $get) {
                                    $businessEntityId = $get('business_entity_id');
                                    if ($businessEntityId) {
                                        return Division::where('business_entity_id', $businessEntityId)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
                                    }
                                    return [];
                                })
                                ->reactive()
                                ->searchable()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $set('region_id', null);
                                    $set('cluster_id', null);
                                })
                                ->columnSpan(6),
                            Select::make('region_id')
                                ->label('Region')
                                ->options(function (callable $get) {
                                    $divisionId = $get('division_id');
                                    if ($divisionId) {
                                        return Region::where('division_id', $divisionId)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
                                    }
                                    return [];
                                })
                                ->reactive()
                                ->searchable()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $set('cluster_id', null);
                                })
                                ->columnSpan(6),
                            Select::make('cluster_id')
                                ->label('Cluster')
                                ->options(function (callable $get) {
                                    $regionId = $get('region_id');
                                    if ($regionId) {
                                        return Cluster::where('region_id', $regionId)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
                                    }
                                    return [];
                                })
                                ->reactive()
                                ->searchable()
                                ->columnSpan(6),
                        ])
                        ->columns(12),
                    Step::make('Additional Information')
                        ->schema([
                            TextInput::make('district')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            TextInput::make('radius')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                            Select::make('status')
                                ->required()
                                ->options([
                                    'MAINTAIN' => 'MAINTAIN',
                                    'UNMAINTAIN' => 'UNMAINTAIN',
                                    'UNPRODUCTIVE' => 'UNPRODUCTIVE',
                                ])
                                ->columnSpan(6),
                            TextInput::make('limit')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(6),
                        ])
                        ->columns(12),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessEntity.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('district')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('owner')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('telp')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('radius')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('limit')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('latlong')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListOutlets::route('/'),
            'create' => Pages\CreateOutlet::route('/create'),
            'edit' => Pages\EditOutlet::route('/{record}/edit'),
        ];
    }
}
