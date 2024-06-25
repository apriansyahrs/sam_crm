<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Imports\UserImport;
use \EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                ExportAction::make()
                    ->color('success')
                    ->icon('heroicon-o-arrow-up-tray'),
                ExcelImportAction::make()
                    ->color("info")
                    ->use(UserImport::class),
            ])->icon('heroicon-m-ellipsis-vertical')
                ->button(),
        ];
    }
}
