<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NooResource\Pages;
use App\Filament\Resources\NooResource\RelationManagers;
use App\Models\Noo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NooResource extends Resource
{
    protected static ?string $model = Noo::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Informasi Umum
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Code')
                            ->maxLength(255),
                        Forms\Components\Select::make('business_entity_id')
                            ->label('Business Entity')
                            ->relationship('businessEntity', 'name')
                            ->required(),
                        Forms\Components\Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Outlet Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2), // Atur jadi dua kolom untuk form yang lebih rapih

                // Kontak & Alamat
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('owner')
                            ->label('Owner')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('optional_phone')
                            ->label('Optional Phone')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(2), // Dua kolom untuk kontak dan alamat

                // Foto & KTP
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('photo_shop_sign')
                            ->label('Photo of Shop Sign')
                            ->required(),
                        Forms\Components\FileUpload::make('photo_front')
                            ->label('Front Photo')
                            ->required(),
                        Forms\Components\FileUpload::make('photo_left')
                            ->label('Left Photo')
                            ->required(),
                        Forms\Components\FileUpload::make('photo_right')
                            ->label('Right Photo')
                            ->required(),
                        Forms\Components\FileUpload::make('photo_ktp')
                            ->label('KTP Photo')
                            ->required(),
                    ])
                    ->columns(2), // Dua kolom untuk foto agar lebih rapi

                // Lokasi & Status
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('latlong')
                            ->label('Latitude/Longitude')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('limit')
                            ->label('Limit')
                            ->numeric()
                            ->placeholder('Enter limit if applicable'),
                    ])
                    ->columns(2),

                // Foto Tambahan & Video
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('video')
                            ->label('Video')
                            ->required(),
                        Forms\Components\TextInput::make('oppo')
                            ->label('Oppo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('vivo')
                            ->label('Vivo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('realme')
                            ->label('Realme')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('samsung')
                            ->label('Samsung')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('xiaomi')
                            ->label('Xiaomi')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                // Metadata
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('created_by')
                            ->label('Created By')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('rejected_at')
                            ->label('Rejected At'),
                        Forms\Components\TextInput::make('rejected_by')
                            ->label('Rejected By')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('confirmed_at')
                            ->label('Confirmed At'),
                        Forms\Components\TextInput::make('confirmed_by')
                            ->label('Confirmed By')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At'),
                        Forms\Components\TextInput::make('approved_by')
                            ->label('Approved By')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('notes')
                            ->label('Notes')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessEntity.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('optional_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ktp_outlet')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cluster.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('photo_shop_sign')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_front')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_left')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_right')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_ktp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('video')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oppo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('realme')
                    ->searchable(),
                Tables\Columns\TextColumn::make('samsung')
                    ->searchable(),
                Tables\Columns\TextColumn::make('xiaomi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fl')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latlong')
                    ->searchable(),
                Tables\Columns\TextColumn::make('limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rejected_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rejected_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('confirmed_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tm.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListNoos::route('/'),
            'create' => Pages\CreateNoo::route('/create'),
            'edit' => Pages\EditNoo::route('/{record}/edit'),
        ];
    }
}
