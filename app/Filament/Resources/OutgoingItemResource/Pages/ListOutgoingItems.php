<?php

namespace App\Filament\Resources\OutgoingItemResource\Pages;

use App\Filament\Resources\OutgoingItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutgoingItems extends ListRecords
{
    protected static string $resource = OutgoingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
