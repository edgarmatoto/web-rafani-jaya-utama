<?php

namespace App\Filament\Resources\IncomingItemResource\Pages;

use App\Filament\Resources\IncomingItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomingItem extends EditRecord
{
    protected static string $resource = IncomingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
