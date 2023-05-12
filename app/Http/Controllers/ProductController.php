<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use App\Http\Requests\ProductRequest;

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
        return view('products.create', compact('inventories', 'unitsOfMeasure'));
    }

    public function search(Request $request)
{
    $search = $request->get('query');
    $products = Product::where('name', 'LIKE', "%{$search}%")->get();

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
            'price' => str_replace(',', '.', str_replace('.', '', $request->price)),
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

    public function update(ProductRequest $request, $id)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'unit_of_measure' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'inventories_id' => 'required|exists:inventories,id'
        ]);

        $product = Product::findOrFail($id);
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->measure = $validated['unit_of_measure'];
        $product->quantity = $validated['quantity'];
        $product->inventories_id = $validated['inventories_id'];
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    }
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso!');
    }
}
