<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::orderBy('name')->get();
        return view('restaurant.index', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'quantity_type' => 'required|string|max:50',
            'price'         => 'required|numeric|min:0',
        ]);

        MenuItem::create($request->only('name','quantity_type','price'));

        return back()->with('success', 'Menu item added successfully.');
    }

    public function update(Request $request, MenuItem $restaurant)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'quantity_type' => 'required|string|max:50',
            'price'         => 'required|numeric|min:0',
        ]);

        $restaurant->update($request->only('name','quantity_type','price'));

        return back()->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $restaurant)
    {
        $restaurant->delete();
        return back()->with('success', 'Menu item deleted.');
    }
}
