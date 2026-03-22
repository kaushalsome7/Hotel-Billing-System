@extends('layouts.app')
@section('title','Bill — '.$customer->name)

@section('content')

<div class="flex items-start justify-between mb-7 no-print">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Generate Bill</h2>
        <p class="text-slate-400 text-sm mt-1">Bill for {{ $customer->name }}</p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()"
                class="flex items-center gap-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-print text-xs"></i> Print
        </button>
        <a href="{{ route('billing.index') }}"
           class="flex items-center gap-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
    </div>
</div>

@php $ic = "w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-sm text-slate-800 transition-all"; @endphp

<div class="grid grid-cols-3 gap-5 items-start">

    {{-- ── Left: Order Entry Panels ── --}}
    <div class="col-span-1 space-y-4 no-print">

        {{-- Restaurant Orders --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 pb-3 border-b border-slate-100">
                Add Restaurant Orders
            </p>
            <form action="{{ route('billing.addOrder',$customer) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Menu Item</label>
                    <select name="menu_item_id" required class="{{ $ic }}">
                        <option value="">— Select Item —</option>
                        @foreach($menuItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->quantity_type }}) — ₹{{ number_format($item->price,2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" required class="{{ $ic }}">
                </div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add to Bill
                </button>
            </form>
        </div>

        {{-- Room Service Usage --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 pb-3 border-b border-slate-100">
                Add Room Service
            </p>
            <form action="{{ route('billing.addServiceUsage',$customer) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Service</label>
                    <select name="room_service_id" required class="{{ $ic }}">
                        <option value="">— Select Service —</option>
                        @foreach($roomServices as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Price Charged (₹)</label>
                    <input type="number" name="price_charged" min="0" step="0.01" placeholder="500" required class="{{ $ic }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Times Used</label>
                    <input type="number" name="times_used" value="1" min="1" required class="{{ $ic }}">
                </div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add to Bill
                </button>
            </form>
        </div>

        {{-- Adjustments --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 pb-3 border-b border-slate-100">
                Discounts &amp; Charges
            </p>
            <form action="{{ route('billing.saveAdjustments',$customer) }}" method="POST" class="space-y-4">
                @csrf
                @foreach(['room'=>'Room','restaurant'=>'Restaurant','grand'=>'Grand Total'] as $prefix=>$label)
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-2">{{ $label }}</p>
                    @php $adj = $adjustments[$prefix] ?? null; @endphp
                    <div class="grid grid-cols-2 gap-2">
                        <select name="{{ $prefix }}_adj_type" class="{{ $ic }} text-xs">
                            <option value="none"           {{ (!$adj||$adj['mode']==='none') ? 'selected':'' }}>None</option>
                            <option value="discount_percent" {{ ($adj&&$adj['mode']==='discount'&&$adj['type']==='percent') ? 'selected':'' }}>Discount %</option>
                            <option value="discount_flat"    {{ ($adj&&$adj['mode']==='discount'&&$adj['type']==='flat')    ? 'selected':'' }}>Discount ₹</option>
                            <option value="charge_percent"   {{ ($adj&&$adj['mode']==='charge'  &&$adj['type']==='percent') ? 'selected':'' }}>Charge %</option>
                            <option value="charge_flat"      {{ ($adj&&$adj['mode']==='charge'  &&$adj['type']==='flat')    ? 'selected':'' }}>Charge ₹</option>
                        </select>
                        <input type="number" name="{{ $prefix }}_adj_value"
                               value="{{ $adj['value'] ?? '' }}"
                               min="0" step="0.01" placeholder="0"
                               class="{{ $ic }} text-xs">
                    </div>
                </div>
                @endforeach
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-white text-xs font-semibold py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-check text-[10px]"></i> Apply Adjustments
                </button>
            </form>
        </div>
    </div>

    {{-- ── Right: Printable Bill ── --}}
    <div class="col-span-2">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10" id="billPrint">

            {{-- Bill Header --}}
            <div class="text-center pb-6 border-b border-slate-100 mb-6">
                <h2 class="text-3xl font-bold text-yellow-500 mb-1" style="font-family:'Playfair Display',serif">BillifyStay</h2>
                <p class="text-slate-400 text-xs tracking-widest uppercase">Billing Management System &bull; Tax Invoice</p>
            </div>

            {{-- Customer Meta --}}
            <div class="grid grid-cols-3 gap-4 mb-7 pb-6 border-b border-slate-100">
                @foreach([
                    ['Guest Name',    $customer->name],
                    ['Aadhar Number', $customer->aadhar_number],
                    ['Check-in Date', \Carbon\Carbon::parse($customer->check_in_date)->format('d M Y')],
                    ['Days Staying',  $customer->number_of_days.' day(s)'],
                    ['Room',          $customer->room ? 'Room '.$customer->room->room_number.' — '.$customer->room->description : 'No room booked'],
                    ['Bill Date',     now()->format('d M Y')],
                ] as [$label,$value])
                <div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest font-medium">{{ $label }}</p>
                    <p class="text-sm font-medium text-slate-800 mt-1 {{ $label==='Aadhar Number' ? 'font-mono text-xs':'' }}">{{ $value }}</p>
                </div>
                @endforeach
            </div>

            {{-- 1. Room Billing --}}
            @if($customer->room)
            @php
                $roomBase = $customer->room->price_per_day * $customer->number_of_days;
                $roomAdj  = $adjustments['room'] ?? null;
                $roomFinal= $roomBase;
                if($roomAdj){ $amt=($roomAdj['type']==='percent') ? round($roomBase*abs($roomAdj['value'])/100,2) : abs($roomAdj['value']); $roomFinal=$roomAdj['mode']==='discount'?$roomBase-$amt:$roomBase+$amt; }
            @endphp
            <div class="mb-6">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">Room Charges</p>
                <div class="flex justify-between items-start py-2">
                    <div>
                        <p class="text-sm text-slate-800">{{ $customer->room->description }} — Room {{ $customer->room->room_number }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">₹{{ number_format($customer->room->price_per_day,2) }} × {{ $customer->number_of_days }} day(s)</p>
                    </div>
                    <p class="text-sm font-medium text-slate-800">₹{{ number_format($roomBase,2) }}</p>
                </div>
                @if($roomAdj)
                <div class="flex justify-between text-xs py-1 {{ $roomAdj['mode']==='discount'?'text-emerald-600':'text-red-500' }}">
                    <span>{{ ucfirst($roomAdj['mode']) }} {{ $roomAdj['type']==='percent'?'('.$roomAdj['value'].'%)':'(flat)' }}</span>
                    <span>{{ $roomAdj['mode']==='discount'?'−':'+' }} ₹{{ number_format(abs($roomBase-$roomFinal),2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-semibold pt-1 border-t border-slate-100 mt-1">
                    <span>Room Subtotal</span><span>₹{{ number_format($roomFinal,2) }}</span>
                </div>
                @endif
            </div>
            @else @php $roomFinal=0; @endphp
            @endif

            {{-- 2. Restaurant Billing --}}
            @if($orders->isNotEmpty())
            @php
                $restBase=0;
                $restAdj=$adjustments['restaurant']??null;
            @endphp
            <div class="mb-6">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">Restaurant Orders</p>
                @foreach($orders as $order)
                @php $lt=$order->menuItem->price*$order->quantity; $restBase+=$lt; @endphp
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <div>
                        <p class="text-sm text-slate-800">{{ $order->menuItem->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $order->menuItem->quantity_type }} × {{ $order->quantity }} @ ₹{{ number_format($order->menuItem->price,2) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="text-sm font-medium text-slate-800">₹{{ number_format($lt,2) }}</p>
                        <form action="{{ route('billing.removeOrder',[$customer,$order]) }}" method="POST" class="no-print">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-400 transition-colors text-xs" title="Remove">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
                @php
                    $restFinal=$restBase;
                    if($restAdj){ $amt=($restAdj['type']==='percent')?round($restBase*abs($restAdj['value'])/100,2):abs($restAdj['value']); $restFinal=$restAdj['mode']==='discount'?$restBase-$amt:$restBase+$amt; }
                @endphp
                @if($restAdj)
                <div class="flex justify-between text-xs py-1 mt-1 {{ $restAdj['mode']==='discount'?'text-emerald-600':'text-red-500' }}">
                    <span>{{ ucfirst($restAdj['mode']) }} {{ $restAdj['type']==='percent'?'('.$restAdj['value'].'%)':'(flat)' }}</span>
                    <span>{{ $restAdj['mode']==='discount'?'−':'+' }} ₹{{ number_format(abs($restBase-$restFinal),2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-semibold pt-1 border-t border-slate-100 mt-1">
                    <span>Restaurant Subtotal</span><span>₹{{ number_format($restFinal,2) }}</span>
                </div>
                @endif
            </div>
            @else @php $restFinal=0; @endphp
            @endif

            {{-- 3. Room Services Billing --}}
            @if($serviceUsages->isNotEmpty())
            @php $svcBase=0; @endphp
            <div class="mb-6">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">Room Services</p>
                @foreach($serviceUsages as $usage)
                @php $lt=$usage->price_charged*$usage->times_used; $svcBase+=$lt; @endphp
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <div>
                        <p class="text-sm text-slate-800">{{ $usage->roomService->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">₹{{ number_format($usage->price_charged,2) }} × {{ $usage->times_used }} time(s)</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="text-sm font-medium text-slate-800">₹{{ number_format($lt,2) }}</p>
                        <form action="{{ route('billing.removeServiceUsage',[$customer,$usage]) }}" method="POST" class="no-print">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-400 transition-colors text-xs" title="Remove">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else @php $svcBase=0; @endphp
            @endif

            {{-- 4. Final Bill Summary --}}
            @php
                $grandAdj=$adjustments['grand']??null;
                $subTotal=($roomFinal??0)+($restFinal??0)+($svcBase??0);
                $grandFinal=$subTotal;
                if($grandAdj){ $amt=($grandAdj['type']==='percent')?round($subTotal*abs($grandAdj['value'])/100,2):abs($grandAdj['value']); $grandFinal=$grandAdj['mode']==='discount'?$subTotal-$amt:$subTotal+$amt; }
            @endphp

            <div class="bg-slate-50 rounded-xl p-5 mt-2">
                @if($customer->room)
                <div class="flex justify-between text-sm text-slate-500 py-1.5"><span>Room</span><span>₹{{ number_format($roomFinal,2) }}</span></div>
                @endif
                @if($orders->isNotEmpty())
                <div class="flex justify-between text-sm text-slate-500 py-1.5"><span>Restaurant</span><span>₹{{ number_format($restFinal,2) }}</span></div>
                @endif
                @if($serviceUsages->isNotEmpty())
                <div class="flex justify-between text-sm text-slate-500 py-1.5"><span>Room Services</span><span>₹{{ number_format($svcBase,2) }}</span></div>
                @endif

                <div class="flex justify-between text-sm text-slate-500 py-1.5 border-t border-slate-200 mt-1 pt-2">
                    <span>Subtotal</span><span>₹{{ number_format($subTotal,2) }}</span>
                </div>

                @if($grandAdj)
                <div class="flex justify-between text-sm py-1 {{ $grandAdj['mode']==='discount'?'text-emerald-600':'text-red-500' }}">
                    <span>Grand Total {{ ucfirst($grandAdj['mode']) }} {{ $grandAdj['type']==='percent'?'('.$grandAdj['value'].'%)':'(flat)' }}</span>
                    <span>{{ $grandAdj['mode']==='discount'?'−':'+' }} ₹{{ number_format(abs($subTotal-$grandFinal),2) }}</span>
                </div>
                @endif

                <div class="flex justify-between text-base font-bold text-slate-900 border-t-2 border-slate-900 pt-3 mt-2">
                    <span>Grand Total</span>
                    <span>₹{{ number_format($grandFinal,2) }}</span>
                </div>
            </div>

            <p class="text-center text-[10px] text-slate-400 mt-8 pt-5 border-t border-slate-100">
                Thank you for staying at our hotel &bull; Bill generated on {{ now()->format('d M Y, h:i A') }} &bull; Computer-generated invoice
            </p>

        </div>
    </div>

</div>
@endsection
