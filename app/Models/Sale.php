<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['final_value', 'sales_date', 'product_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
