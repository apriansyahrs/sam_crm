<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletResource\Pages;
use App\Filament\Resources\OutletResource\RelationManagers;
use App\Models\Outlet;
use Filament\Forms;
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
                //
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
