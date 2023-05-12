<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

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

    public function getPriceFormattedAttribute()
    {
        $formatter = new NumberFormatter('pt-BR', NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->attributes['price'], 'BRL');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(',', '.', str_replace('.', '', $value));
    }
}
