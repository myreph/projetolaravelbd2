<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Product;

class SaleController extends Controller
{    
    public function index()
    {
        $sales = Sale::all();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::paginate(10); // Modificado para usar a paginação
        $subtotal = 0;
        return view('sales.create', compact('products', 'subtotal'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        try {
            DB::transaction(function () use ($request) {
                $products = $request->input('products');
                $finalValue = 0;
                $errors = [];
    
                $sale = new Sale();
                $sale->sales_date = now();
    
                foreach ($products as $product) {
                    $productModel = Product::findOrFail($product['id']);
                    $quantity = $product['quantity'];
    
                    if ($quantity > $productModel->quantity) {
                        $errors[] = "{$productModel->name}: A quantidade solicitada é maior que o estoque disponível.";
                    } else {
                        $productModel->quantity -= $quantity;
                        $productModel->save();
                        $finalValue += $productModel->price * $quantity;
    
                        $sale->products()->attach($productModel->id, ['quantity' => $quantity]);
                    }
                }
    
                if (!empty($errors)) {
                    return redirect()->back()->withErrors($errors);
                }
    
                $sale->final_value = $finalValue;
                $sale->save();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Ocorreu um erro inesperado. Por favor, tente novamente.');
        }
    
        return redirect()->route('sales.index')->with('success', 'Venda realizada com sucesso!');
    }
    
}
