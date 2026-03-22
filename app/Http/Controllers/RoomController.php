<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number'   => 'required|string|max:20|unique:rooms,room_number',
            'floor'         => 'nullable|integer|min:0',
            'description'   => 'required|string|max:255',
            'capacity'      => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
        ]);

        Room::create($request->only('room_number','floor','description','capacity','price_per_day'));

        return back()->with('success', 'Room added successfully.');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number'   => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'floor'         => 'nullable|integer|min:0',
            'description'   => 'required|string|max:255',
            'capacity'      => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
        ]);

        $room->update($request->only('room_number','floor','description','capacity','price_per_day'));

        return back()->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('success', 'Room deleted.');
    }
}
