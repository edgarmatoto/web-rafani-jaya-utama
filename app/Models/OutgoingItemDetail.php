<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingItemDetail extends Model
{
    use HasFactory;

    protected $table = 'outgoing_items_details';

    public $timestamps = false;

    protected $fillable = ['outgoing_items_receipt_number', 'item_id', 'quantity', 'unit_price', 'subtotal'];

    public function outgoingItem(): BelongsTo {
        return $this->belongsTo(OutgoingItem::class, 'outgoing_items_receipt_number', 'receipt_number');
    }

    public function item(): BelongsTo {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
