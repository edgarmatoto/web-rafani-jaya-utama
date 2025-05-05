<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $table = 'outgoing_items';

    public $timestamps = false;

    public $primaryKey = 'receipt_number';

    protected $fillable = ['receipt_number', 'total_price', 'buyer', 'address', 'created_at'];

    public function outgoingItemDetail(): HasMany
    {
        return $this->hasMany(OutgoingItemDetail::class, 'outgoing_items_receipt_number', 'receipt_number');
    }
}
