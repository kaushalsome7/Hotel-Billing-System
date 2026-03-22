<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Room;
use App\Models\MenuItem;
use App\Models\RoomService;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalCustomers' => Customer::count(),
            'totalRooms'     => Room::count(),
            'totalMenuItems' => MenuItem::count(),
            'totalServices'  => RoomService::count(),
        ]);
    }
}
