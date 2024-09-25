<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Filament\Resources\VisitResource\RelationManagers;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian untuk informasi umum
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('visit_date')
                            ->label('Visit Date')
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Visitor')
                            ->required(),
                        Forms\Components\Select::make('outlet_id')
                            ->relationship('outlet', 'name')
                            ->label('Outlet')
                            ->required(),
                    ])
                    ->columns(3),

                // Bagian untuk lokasi dan waktu
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('visit_type')
                            ->label('Visit Type')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latlong_in')
                            ->label('Lat/Long In')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latlong_out')
                            ->label('Lat/Long Out')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('check_in_time')
                            ->label('Check-in Time')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set, callable $get) => self::calculateDuration($set, $get)),
                        Forms\Components\DateTimePicker::make('check_out_time')
                            ->label('Check-out Time')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set, callable $get) => self::calculateDuration($set, $get)),
                        Forms\Components\TextInput::make('visit_duration')
                            ->label('Visit Duration (in minutes)')
                            ->numeric()
                            ->readOnly(),
                    ])
                    ->columns(3),

                // Bagian untuk laporan kunjungan
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('visit_report')
                            ->label('Visit Report')
                            ->placeholder('Describe the visit details')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('transaction')
                            ->label('Transaction Details')
                            ->options([
                                'yes' => 'Yes',
                                'no' => 'No',
                            ])
                            ->required(),
                    ])
                    ->columns(1),

                // Bagian untuk gambar kunjungan
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('picture_visit_in')
                            ->label('Picture at Check-in'),
                        Forms\Components\FileUpload::make('picture_visit_out')
                            ->label('Picture at Check-out'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visit_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('outlet.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latlong_in')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latlong_out')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_in_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction'),
                Tables\Columns\TextColumn::make('visit_duration')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }

    protected static function calculateDuration(callable $set, callable $get)
    {
        $checkIn = $get('check_in_time');
        $checkOut = $get('check_out_time');

        if ($checkIn && $checkOut) {
            $checkInTime = \Carbon\Carbon::parse($checkIn);
            $checkOutTime = \Carbon\Carbon::parse($checkOut);

            // Hitung durasi dalam menit
            $duration = $checkOutTime->diffInMinutes($checkInTime);

            // Set 'visit_duration' secara otomatis
            $set('visit_duration', $duration);
        }
    }
}
