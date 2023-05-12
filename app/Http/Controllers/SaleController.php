<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function create()
{
    $products = Product::all();
    return view('sales.create', compact('products'));
}

public function store(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    $final_value = 0;

    foreach ($request->products as $item) {
        $product = Product::find($item['id']);

        if ($item['quantity'] > $product->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'A quantidade solicitada é maior que o estoque disponível.']);
        }

        // Atualize a quantidade do produto no estoque
        $product->quantity -= $item['quantity'];
        $product->save();

        // Calcule o valor final da venda
        $final_value += $product->price * $item['quantity'];
    }

    // Armazene a venda no banco de dados
    $sale = Sale::create([
        'final_value' => $final_value,
        'sales_date' => date('Y-m-d'),
    ]);

    // Vincule os produtos à venda
    $sale->products()->attach(collect($request->products)->pluck('id', 'quantity')->toArray());

    return redirect()->route('sales.create')->with('success', 'Venda realizada com sucesso!');
}


}
