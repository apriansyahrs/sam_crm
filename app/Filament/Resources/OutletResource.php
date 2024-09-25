<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletResource\Pages;
use App\Filament\Resources\OutletResource\RelationManagers;
use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Outlet;
use App\Models\Region;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                // Bagian Informasi Outlet
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('code')
                            ->label('Outlet Code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Outlet Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner')
                            ->label('Owner')
                            ->maxLength(255),
                        TextInput::make('telp')
                            ->label('Phone Number')
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('latlong')
                            ->label('Latitude/Longitude')
                            ->maxLength(255)
                            ->hint('Format: Latitude,Longitude'),
                    ])
                    ->columns(2) // Atur agar tampil dalam dua kolom

                    // Bagian Informasi Bisnis
                    ->schema([
                        Select::make('business_entity_id')
                            ->label('Business Entity')
                            ->options(BusinessEntity::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                            ->required()
                            ->reactive(),
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
                            ->searchable(),
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
                            ->searchable(),
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
                            ->searchable(),
                    ])
                    ->columns(2), // Pengelompokan dalam dua kolom

                // Bagian Informasi Tambahan
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('district')
                            ->label('District')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('radius')
                            ->label('Radius')
                            ->required()
                            ->numeric(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'MAINTAIN' => 'MAINTAIN',
                                'UNMAINTAIN' => 'UNMAINTAIN',
                                'UNPRODUCTIVE' => 'UNPRODUCTIVE',
                            ])
                            ->required(),
                        TextInput::make('limit')
                            ->label('Limit')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),

                // Bagian Dokumen dan Foto
                Forms\Components\Card::make()
                    ->schema([
                        FileUpload::make('photo_shop_sign')
                            ->label('Photo Shop Sign')
                            ->required(),
                        FileUpload::make('photo_front')
                            ->label('Photo Front')
                            ->required(),
                        FileUpload::make('photo_left')
                            ->label('Photo Left')
                            ->required(),
                        FileUpload::make('photo_right')
                            ->label('Photo Right')
                            ->required(),
                        FileUpload::make('photo_ktp')
                            ->label('KTP Photo')
                            ->required(),
                        FileUpload::make('video')
                            ->label('Video')
                            ->required(),
                    ])
                    ->columns(2), // Semua file dalam dua kolom untuk lebih rapi
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('businessEntity.name')
                    ->toggleable(),
                TextColumn::make('division.name')
                    ->toggleable(),
                TextColumn::make('region.name')
                    ->toggleable(),
                TextColumn::make('cluster.name')
                    ->toggleable(),
                TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('address')
                    ->toggleable(),
                TextColumn::make('district')
                    ->toggleable(),
                TextColumn::make('owner')
                    ->toggleable(),
                TextColumn::make('telp')
                    ->toggleable(),
                TextColumn::make('radius')
                    ->toggleable(),
                TextColumn::make('limit')
                    ->toggleable(),
                TextColumn::make('latlong')
                    ->formatStateUsing(function ($state) {
                        // Mengasumsikan latlong dalam format "latitude,longitude"
                        $coordinates = explode(',', $state);
                        $latitude = trim($coordinates[0]);
                        $longitude = trim($coordinates[1]);

                        // Membentuk URL Google Maps
                        $url = "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";

                        // Mengembalikan tautan HTML
                        return "<a href='{$url}' target='_blank'>Lihat Lokasi</a>";
                    })
                    ->html()
                    ->toggleable(),
                TextColumn::make('status')
                    ->toggleable(),
                TextColumn::make('TM')
                    ->label('TM')
                    ->formatStateUsing(function ($record) {
                        $user = User::where('division_id', $record->division_id)
                            ->where('region_id', $record->region_id)
                            ->where('cluster_id', $record->cluster_id)
                            ->where('role_id', 3)
                            ->first();
                        return $user->tm->nama_lengkap ?? '-';
                    }),
                TextColumn::make('DSC')
                    ->label('DSC')
                    ->formatStateUsing(function ($record) {
                        $user = User::where('division_id', $record->division_id)
                            ->where('region_id', $record->region_id)
                            ->where('role_id', 2)
                            ->first();
                        return $user->nama_lengkap ?? '-';
                    }),
                TextColumn::make('DSF')
                    ->label('DSF')
                    ->formatStateUsing(function ($record) {
                        $user = User::where('division_id', $record->division_id)
                            ->where('region_id', $record->region_id)
                            ->where('cluster_id', $record->cluster_id)
                            ->where('role_id', 3)
                            ->first();
                        return $user->nama_lengkap ?? '-';
                    }),
                TextColumn::make('photo_shop_sign')
                    ->formatStateUsing(function ($record) {
                        return $record->photo_shop_sign
                            ? "<a href='" . asset('storage/' . $record->photo_shop_sign) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
                TextColumn::make('photo_front')
                    ->formatStateUsing(function ($record) {
                        return $record->photo_front
                            ? "<a href='" . asset('storage/' . $record->photo_front) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
                TextColumn::make('photo_left')
                    ->formatStateUsing(function ($record) {
                        return $record->photo_left
                            ? "<a href='" . asset('storage/' . $record->photo_left) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
                TextColumn::make('photo_right')
                    ->formatStateUsing(function ($record) {
                        return $record->photo_right
                            ? "<a href='" . asset('storage/' . $record->photo_right) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
                TextColumn::make('photo_ktp')
                    ->formatStateUsing(function ($record) {
                        return $record->photo_ktp
                            ? "<a href='" . asset('storage/' . $record->photo_ktp) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
                TextColumn::make('video')
                    ->label('Video')
                    ->formatStateUsing(function ($record) {
                        return $record->video
                            ? "<a href='" . asset('storage/' . $record->video) . "' target='_blank'>View</a>"
                            : '-';
                    })
                    ->html(),
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

    protected function getTableQuery()
    {
        // Query utama Anda untuk mengambil data dari database
        return User::query();
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
