<?php

namespace App\Filament\Resources\OutletResource\Pages;

use App\Filament\Resources\OutletResource;
use App\Imports\OutletImport;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListOutlets extends ListRecords
{
    protected static string $resource = OutletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                ExportAction::make()
                    ->label('Export')
                    ->color('success')
                    ->icon('heroicon-o-arrow-up-tray'),
                ExcelImportAction::make()
                    ->color("info")
                    ->use(OutletImport::class),
            ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->color('warning'),
        ];
    }
}
