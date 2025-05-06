<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Item extends Model
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'items';

    protected $fillable = ['code', 'name', 'qty', 'price'];
}
