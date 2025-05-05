<?php

namespace App\Filament\Resources\OutgoingItemResource\Pages;

use App\Filament\Resources\OutgoingItemResource;
use App\Models\Item;
use App\Models\OutgoingItem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOutgoingItem extends CreateRecord
{
    protected static string $resource = OutgoingItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);
    }

    protected function afterCreate(): void {
        $outgoingItem = $this->record;

        $total = 0;

        foreach ($outgoingItem as $outgoingItemDetail) {
            $total += $outgoingItemDetail->subtotal;

            $item = $outgoingItemDetail->item;
            $item->stock -= $outgoingItemDetail->quantity;
            $item->save();
        }
    }
}
