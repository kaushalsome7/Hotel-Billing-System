@extends('layouts.app')
@section('title','Billing')

@section('content')

<div class="mb-7">
    <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Billing</h2>
    <p class="text-slate-400 text-sm mt-1">Select a customer to generate their bill — a customer must be selected first</p>
</div>

@if($customers->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-16 text-center">
        <i class="fa-solid fa-users-slash text-5xl text-slate-200 block mb-4"></i>
        <h3 class="text-base font-semibold text-slate-700 mb-2">No Customers Found</h3>
        <p class="text-slate-400 text-sm mb-6">Check in a customer first before generating a bill.</p>
        <a href="{{ route('customers.create') }}"
           class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-user-plus text-xs"></i> Check In Customer
        </a>
    </div>
@else
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-slate-100">
                    @foreach(['Customer','Aadhar No.','Room','Check-in Date','Days','Expected Check-out','Action'] as $h)
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($customers as $customer)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($customer->photo_path)
                                <img src="{{ asset('storage/'.$customer->photo_path) }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($customer->name,0,2)) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-slate-800">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $customer->aadhar_number }}</td>
                    <td class="px-5 py-4 text-sm text-slate-600">
                        {{ $customer->room ? 'Room '.$customer->room->room_number : '—' }}
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($customer->check_in_date)->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $customer->number_of_days }}d</td>
                    <td class="px-5 py-4 text-sm text-slate-600">
                        {{ \Carbon\Carbon::parse($customer->check_in_date)->addDays($customer->number_of_days)->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('billing.show',$customer) }}"
                           class="inline-flex items-center gap-1.5 bg-yellow-500 hover:bg-yellow-400 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-file-invoice text-[11px]"></i> Generate Bill
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
