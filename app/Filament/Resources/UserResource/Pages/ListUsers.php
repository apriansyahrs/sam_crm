<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Imports\UserImport;
use \EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                ExportAction::make()
                    ->label('Export')
                    ->color('success')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->exports([
                        ExcelExport::make()->fromTable()->withColumns([
                            Column::make('name')->heading('Nama'),
                            Column::make('username')->heading('Username'),
                            Column::make('position.name')->heading('Posisi'),
                            Column::make('businessEntity.name')->heading('Badan Usaha'),
                            Column::make('division.name')->heading('Divisi'),
                            Column::make('region.name')->heading('Region'),
                            Column::make('cluster.name')->heading('Cluster'),
                            Column::make('tm.name')->heading('TM'),
                        ])->withFilename('export_ticket_' . date('Y-m-d')),
                    ]),
                ExcelImportAction::make()
                    ->color("info")
                    ->use(UserImport::class),
            ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->color('warning'),
        ];
    }
}
