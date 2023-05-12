<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
{
    $inventories = Inventory::all();
    return view('inventories.index', compact('inventories'));
}


public function create()
{
    return view('inventories.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $inventory = new Inventory([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    $inventory->save();

    return redirect()->route('inventories.index')->with('success', 'Inventário cadastrado com sucesso!');
}


    public function show(\App\Models\Inventory $inventory)
{
    $products = $inventory->products;
    return view('inventories.show', compact('inventory', 'products'));
}

public function edit(Inventory $inventory)
{
    return view('inventories.edit', compact('inventory'));
}

public function update(Request $request, Inventory $inventory)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $inventory->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect()->route('inventories.index')->with('success', 'Inventário atualizado com sucesso!');
}

public function destroy(Inventory $inventory)
{
    $inventory->delete();

    return redirect()->route('inventories.index')->with('success', 'Inventário deletado com sucesso!');
}
}
