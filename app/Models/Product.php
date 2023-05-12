<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'inventories_id',
        'quantity',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventories_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity');
    }
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(',', '.', $value);
    }
    public function getPriceFormattedAttribute()
    {
        // Defina a formatação do preço aqui
        return number_format($this->price, 2, ',', '.');
    }
}

