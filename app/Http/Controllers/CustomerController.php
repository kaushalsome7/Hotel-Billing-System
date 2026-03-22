<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    /**
     * List all customers.
     */
    public function index()
    {
        $customers = Customer::with('room')->latest()->get();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show create / check-in form.
     */
    public function create()
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('customers.create', compact('rooms'));
    }

    /**
     * Store a newly checked-in customer.
     * Handles Aadhar image upload and webcam photo (base64).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'dob'              => 'required|date|before:today',
            'aadhar_number'    => 'required|string|min:12|max:14',
            'aadhar_image'     => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'check_in_purpose' => 'required|string|max:255',
            'check_in_date'    => 'required|date',
            'number_of_days'   => 'required|integer|min:1|max:365',
            'room_id'          => 'nullable|exists:rooms,id',
            'photo_data'       => 'nullable|string', // base64 from webcam
        ]);

        // ── Save Aadhar image ──
        $aadharPath = $request->file('aadhar_image')
                              ->store('aadhar_images', 'public');

        // ── Save webcam photo (base64 → file) ──
        $photoPath = null;
        if ($request->filled('photo_data')) {
            $photoPath = $this->saveBase64Photo($request->photo_data);
        }

        // ── Normalize Aadhar number (strip spaces) ──
        $aadharClean = preg_replace('/\s+/', ' ', trim($request->aadhar_number));

        Customer::create([
            'name'             => $request->name,
            'dob'              => $request->dob,
            'aadhar_number'    => $aadharClean,
            'aadhar_image_path'=> $aadharPath,
            'photo_path'       => $photoPath,
            'check_in_purpose' => $request->check_in_purpose,
            'check_in_date'    => $request->check_in_date,
            'number_of_days'   => $request->number_of_days,
            'room_id'          => $request->room_id ?: null,
        ]);

        return redirect()->route('customers.index')
                         ->with('success', 'Customer checked in successfully.');
    }

    /**
     * Show a single customer's details.
     */
    public function show(Customer $customer)
    {
        $customer->load('room');
        return view('customers.show', compact('customer'));
    }

    /**
     * Delete a customer record.
     * Also removes uploaded files from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->photo_path) {
            Storage::disk('public')->delete($customer->photo_path);
        }
        if ($customer->aadhar_image_path) {
            Storage::disk('public')->delete($customer->aadhar_image_path);
        }

        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Customer record deleted.');
    }

    private function saveBase64Photo(string $base64): string
    {
        // Strip data-url prefix: data:image/jpeg;base64,....
        $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $data = base64_decode($data);

        $filename = 'customer_photos/' . uniqid('photo_', true) . '.jpg';
        Storage::disk('public')->put($filename, $data);

        return $filename;
    }
}

