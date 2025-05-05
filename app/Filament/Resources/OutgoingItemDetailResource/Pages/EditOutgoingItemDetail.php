<?php

namespace App\Filament\Resources\OutgoingItemDetailResource\Pages;

use App\Filament\Resources\OutgoingItemDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutgoingItemDetail extends EditRecord
{
    protected static string $resource = OutgoingItemDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
