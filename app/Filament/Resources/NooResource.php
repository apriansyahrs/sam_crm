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
                Forms\Components\TextInput::make('code')
                    ->maxLength(255),
                Forms\Components\Select::make('business_entity_id')
                    ->relationship('businessEntity', 'name')
                    ->required(),
                Forms\Components\Select::make('division_id')
                    ->relationship('division', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('owner')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('optional_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ktp_outlet')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('district')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),
                Forms\Components\Select::make('cluster_id')
                    ->relationship('cluster', 'name')
                    ->required(),
                Forms\Components\TextInput::make('photo_shop_sign')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_front')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_left')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_right')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_ktp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('oppo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vivo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('realme')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('samsung')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('xiaomi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fl')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('latlong')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('limit')
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('created_by')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('rejected_at'),
                Forms\Components\TextInput::make('rejected_by')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('confirmed_at'),
                Forms\Components\TextInput::make('confirmed_by')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('approved_at'),
                Forms\Components\TextInput::make('approved_by')
                    ->maxLength(255),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255),
                Forms\Components\Select::make('tm_id')
                    ->relationship('tm', 'name')
                    ->required(),
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
