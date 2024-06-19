<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClusterResource\Pages;
use App\Filament\Resources\ClusterResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClusterResource extends Resource
{
    protected static ?string $model = Cluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\Select::make('business_entity_id')
                ->label('Business Entity')
                ->options(BusinessEntity::all()->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $unit = BusinessEntity::find($state);
                    if ($unit) {
                        $divisionId = (int) $get('division_id');
                        if ($divisionId && $division = Division::find($divisionId)) {
                            if ($division->business_entity_id !== $unit->id) {
                                $set('division_id', null);
                                $set('region_id', null);
                            }
                        }
                    } else {
                        $set('division_id', null);
                        $set('region_id', null);
                    }
                })
                ->reactive(),

            Forms\Components\Select::make('division_id')
                ->label('Division')
                ->options(function (callable $get) {
                    $unit = BusinessEntity::find($get('business_entity_id'));
                    if ($unit) {
                        return $unit->divisions->pluck('name', 'id');
                    }

                    return Division::all()->pluck('name', 'id');
                })
                ->searchable()
                ->required()
                ->visible(fn (callable $get) => $get('business_entity_id') !== null)
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    if ($state === null) {
                        $set('region_id', null);
                    }
                })
                ->reactive(),

            Forms\Components\Select::make('region_id')
                ->label('Region')
                ->options(function (callable $get) {
                    $division = Division::find($get('division_id'));
                    if ($division) {
                        return $division->regions->pluck('name', 'id');
                    }

                    return Region::all()->pluck('name', 'id');
                })
                ->searchable()
                ->required()
                ->visible(fn (callable $get) => $get('division_id') !== null)
                ->reactive(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessEntity.name'),
                Tables\Columns\TextColumn::make('division.name'),
                Tables\Columns\TextColumn::make('region.name'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('business_entity_id')
                    ->options(BusinessEntity::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('division_id')
                    ->options(Division::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('region_id')
                    ->options(Region::pluck('name', 'id')),
            ])
            ->defaultSort('name', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClusters::route('/'),
        ];
    }
}
