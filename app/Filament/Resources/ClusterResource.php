<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClusterResource\Pages;
use App\Filament\Resources\ClusterResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
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
                Tables\Columns\TextColumn::make('businessEntity.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->toggleable(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading()
            ->groups([
                Group::make('businessEntity.name')
                    ->collapsible(),
                Group::make('division.name')
                    ->collapsible(),
                Group::make('region.name')
                    ->collapsible(),
            ])
            ->groupingDirectionSettingHidden();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClusters::route('/'),
        ];
    }
}
