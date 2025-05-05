<?php

namespace App\Filament\Resources\OutgoingItemDetailResource\Pages;

use App\Filament\Resources\OutgoingItemDetailResource;
use App\Models\Item;
use App\Models\OutgoingItem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOutgoingItemDetail extends CreateRecord
{
    protected static string $resource = OutgoingItemDetailResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $item = Item::find($data['item_id']);
        $data['subtotal'] = $item->price * $data['quantity'];
        return $data;
    }

    protected function afterCreate(): void {
        $outgoingItemDetail = $this->record;

        $item = Item::find($outgoingItemDetail->item_id);
        if ($item) {
            $item->decrement('stock', $outgoingItemDetail->quantity);
        };

        $receipt = OutgoingItem::find($outgoingItemDetail['outgoing_items_receipt_number']);
        if ($receipt) {
            $receipt->increment('total_price', $outgoingItemDetail->subtotal);
        }
    }
}
