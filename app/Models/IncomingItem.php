<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomingItem extends Model
{
    use HasFactory;

    protected $table = 'incoming_items';

    protected $fillable = ['qty'];

    public function item(): BelongsTo {
        return $this->belongsTo(Item::class);
    }
}
