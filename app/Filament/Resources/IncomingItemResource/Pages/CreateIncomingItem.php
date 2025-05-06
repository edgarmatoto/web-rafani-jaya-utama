<?php

namespace App\Filament\Resources\IncomingItemResource\Pages;

use App\Filament\Resources\IncomingItemResource;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomingItem extends CreateRecord
{
    protected static string $resource = IncomingItemResource::class;

    protected function afterCreate(): void {
        $record = $this->record;

        $item = Item::find($record->item_id);
        if ($item) {
            $item->increment('qty', $record->qty);
        }
    }
}
