<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanVisitResource\Pages;
use App\Filament\Resources\PlanVisitResource\RelationManagers;
use App\Models\PlanVisit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanVisitResource extends Resource
{
    protected static ?string $model = PlanVisit::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make([
                Forms\Components\Grid::make(2) // Menggunakan grid untuk lebih teratur
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->label('Select User')
                            ->placeholder('Choose a user...'), // Menambahkan placeholder

                        Forms\Components\Select::make('outlet_id')
                            ->relationship('outlet', 'name')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->label('Select Outlet')
                            ->placeholder('Choose an outlet...'), // Menambahkan placeholder
                    ]),

                Forms\Components\DatePicker::make('visit_date')
                    ->required()
                    ->label('Visit Date')
                    ->native(false)
                    ->placeholder('Select visit date...'), // Menambahkan placeholder
            ])
            ->columns(1) // Menambahkan Card dengan single column layout untuk fokus yang lebih baik
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outlet.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outlet.code'),
                Tables\Columns\TextColumn::make('visit_date')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('visit_date', 'desc')
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
            'index' => Pages\ListPlanVisits::route('/'),
            'create' => Pages\CreatePlanVisit::route('/create'),
            'edit' => Pages\EditPlanVisit::route('/{record}/edit'),
        ];
    }
}
