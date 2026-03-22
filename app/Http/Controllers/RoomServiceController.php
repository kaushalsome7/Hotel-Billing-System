<?php

namespace App\Http\Controllers;

use App\Models\RoomService;
use Illuminate\Http\Request;

class RoomServiceController extends Controller
{
    public function index()
    {
        $services = RoomService::orderBy('name')->get();
        return view('room-services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        RoomService::create($request->only('name'));

        return back()->with('success', 'Service added successfully.');
    }

    public function update(Request $request, RoomService $roomService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $roomService->update($request->only('name'));

        return back()->with('success', 'Service updated.');
    }

    public function destroy(RoomService $roomService)
    {
        $roomService->delete();
        return back()->with('success', 'Service deleted.');
    }
}
