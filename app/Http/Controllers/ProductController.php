<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $inventories = Inventory::all();
        $unitsOfMeasure = ['unidade', 'kg', 'g', 'l', 'ml']; // unidades fixas

        // Definir $price_formatted como null por padrão
        $product = new Product(['price_formatted' => null]);

        return view('products.create', compact('product', 'inventories', 'unitsOfMeasure'));
    }

    public function search(Request $request)
{
    $query = $request->get('query');
    $products = Product::where('name', 'like', '%' . $query . '%')->get();
    
    return response()->json(['data' => $products]);
}


    public function store(ProductRequest $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'unit_of_measure' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'inventories_id' => 'required|exists:inventories,id'
        ]);

        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'measure' => $request->unit_of_measure,
            'quantity' => $request->quantity,
            'inventories_id' => $request->inventories_id
        ]);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $inventories = Inventory::all();
        $unitsOfMeasure = ['unidade', 'kg', 'g', 'l', 'ml']; // unidades fixas
        return view('products.edit', compact('product', 'inventories', 'unitsOfMeasure'));
    }
    public function update(Request $request, Product $product)
    {
        $request->merge(['price' => str_replace(',', '.', str_replace('.', '', $request->price))]);
    
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'max:255',
            'price' => 'required|numeric',
            'unit_of_measure' => 'required',
            'quantity' => 'required|integer',
            'inventories_id' => 'required|exists:inventories,id',
        ]);
    
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->unit_of_measure = $request->input('unit_of_measure');
        $product->quantity = $request->input('quantity');
        $product->inventories_id = $request->input('inventories_id');
        
        $product->save();
    
        return redirect()->route('products.index')
                         ->with('success', 'Produto atualizado com sucesso!');
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso!');
    }
}

