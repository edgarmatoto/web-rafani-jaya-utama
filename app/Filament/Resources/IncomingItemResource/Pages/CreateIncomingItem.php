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
        $incomingItem = $this->record;

        $item = Item::find($incomingItem->item_id);
        if ($item) {
            $item->increment('stock', $incomingItem->quantity);
        }
    }
}
