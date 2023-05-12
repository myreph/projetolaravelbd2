<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function test()
    {
        $sales = Sale::all();
        return view('sales.index', compact('sales'));
    }
        
    public function index()
    {
        $sales = Sale::all();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        $subtotal = 0; // Adicione esta linha para definir o subtotal inicial como zero
        return view('sales.create', compact('products', 'subtotal'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($request) {
        $products = $request->input('products');
        $finalValue = 0;
        $errors = [];

        foreach ($products as $product) {
            $productModel = Product::findOrFail($product['id']);
            $quantity = $product['quantity'];

            if ($quantity > $productModel->quantity) {
                $errors[] = "{$productModel->name}: A quantidade solicitada é maior que o estoque disponível.";
            } else {
                $productModel->quantity -= $quantity;
                $productModel->save();
                $finalValue += $productModel->price * $quantity;
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }

        Sale::create([
            'final_value' => $finalValue,
            'sales_date' => now(),
        ]);
    });

    return redirect()->route('sales.index')->with('success', 'Venda realizada com sucesso!');
}

}
