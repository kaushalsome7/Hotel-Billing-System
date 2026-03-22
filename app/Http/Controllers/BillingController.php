<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\RoomService;
use App\Models\RestaurantOrder;
use App\Models\ServiceUsage;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Billing index — list all customers.
     * SRS: "no bill should be produced if no customer is selected".
     * This page forces selection first.
     */
    public function index()
    {
        $customers = Customer::with('room')->latest()->get();
        return view('billing.index', compact('customers'));
    }

    /**
     * Show full bill for a specific customer.
     */
    public function show(Customer $customer)
    {
        $customer->load('room');

        $menuItems    = MenuItem::orderBy('name')->get();
        $roomServices = RoomService::orderBy('name')->get();

        $orders        = RestaurantOrder::with('menuItem')
                            ->where('customer_id', $customer->id)
                            ->get();

        $serviceUsages = ServiceUsage::with('roomService')
                            ->where('customer_id', $customer->id)
                            ->get();

        // Retrieve saved adjustments from session
        $adjustments = session("bill_adjustments_{$customer->id}", []);

        return view('billing.show', compact(
            'customer',
            'menuItems',
            'roomServices',
            'orders',
            'serviceUsages',
            'adjustments'
        ));
    }

    /**
     * Add a restaurant order to this customer's bill.
     */
    public function addOrder(Request $request, Customer $customer)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        // Check if item already exists — increase quantity instead of duplicate
        $existing = RestaurantOrder::where('customer_id', $customer->id)
                                   ->where('menu_item_id', $request->menu_item_id)
                                   ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            RestaurantOrder::create([
                'customer_id'  => $customer->id,
                'menu_item_id' => $request->menu_item_id,
                'quantity'     => $request->quantity,
            ]);
        }

        return back()->with('success', 'Order added to bill.');
    }

    /**
     * Remove a restaurant order line.
     */
    public function removeOrder(Customer $customer, RestaurantOrder $order)
    {
        abort_if($order->customer_id !== $customer->id, 403);
        $order->delete();
        return back()->with('success', 'Order removed.');
    }

    /**
     * Add a room service usage to this customer's bill.
     */
    public function addServiceUsage(Request $request, Customer $customer)
    {
        $request->validate([
            'room_service_id' => 'required|exists:room_services,id',
            'price_charged'   => 'required|numeric|min:0',
            'times_used'      => 'required|integer|min:1',
        ]);

        ServiceUsage::create([
            'customer_id'    => $customer->id,
            'room_service_id'=> $request->room_service_id,
            'price_charged'  => $request->price_charged,
            'times_used'     => $request->times_used,
        ]);

        return back()->with('success', 'Service usage added to bill.');
    }

    /**
     * Remove a service usage line.
     */
    public function removeServiceUsage(Customer $customer, ServiceUsage $usage)
    {
        abort_if($usage->customer_id !== $customer->id, 403);
        $usage->delete();
        return back()->with('success', 'Service usage removed.');
    }

    /**
     * Save discount/extra-charge adjustments to session.
     * SRS: discounts/extra charges can be applied per-section and on grand total,
     *      as percentage or flat amount.
     */
    public function saveAdjustments(Request $request, Customer $customer)
    {
        $request->validate([
            'room_adj_type'       => 'nullable|string',
            'room_adj_value'      => 'nullable|numeric|min:0',
            'restaurant_adj_type' => 'nullable|string',
            'restaurant_adj_value'=> 'nullable|numeric|min:0',
            'grand_adj_type'      => 'nullable|string',
            'grand_adj_value'     => 'nullable|numeric|min:0',
        ]);

        $adjustments = [];

        foreach (['room', 'restaurant', 'grand'] as $prefix) {
            $type  = $request->input("{$prefix}_adj_type", 'none');
            $value = (float) $request->input("{$prefix}_adj_value", 0);

            if ($type !== 'none' && $value > 0) {
                // type format: "discount_percent" | "discount_flat" | "charge_percent" | "charge_flat"
                [$mode, $calcType] = explode('_', $type, 2);

                $adjustments[$prefix] = [
                    'mode'  => $mode,     // 'discount' | 'charge'
                    'type'  => $calcType, // 'percent'  | 'flat'
                    'value' => $value,
                ];
            }
        }

        session(["bill_adjustments_{$customer->id}" => $adjustments]);

        return back()->with('success', 'Adjustments applied.');
    }
}
