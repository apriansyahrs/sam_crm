<?php

namespace App\Filament\Resources\PlanVisitResource\Pages;

use App\Filament\Resources\PlanVisitResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListPlanVisits extends ListRecords
{
    protected static string $resource = PlanVisitResource::class;

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
                    ->color("info"),
            ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->color('warning'),
        ];
    }
}
