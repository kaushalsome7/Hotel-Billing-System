@extends('layouts.app')
@section('title','Customers')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Customers</h2>
        <p class="text-slate-400 text-sm mt-1">Check-in management — Create and view customer records</p>
    </div>
    <a href="{{ route('customers.create') }}"
       class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors duration-150">
        <i class="fa-solid fa-plus text-xs"></i> Check In Customer
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">#</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Photo</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Aadhar No.</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Room</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Purpose</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Check-in</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Days</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($customers as $customer)
            <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                <td class="px-5 py-4 text-xs text-slate-400">{{ $loop->iteration }}</td>
                <td class="px-5 py-4">
                    @if($customer->photo_path)
                        <img src="{{ asset('storage/'.$customer->photo_path) }}"
                             class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-100">
                    @else
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-semibold">
                            {{ strtoupper(substr($customer->name,0,2)) }}
                        </div>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="text-sm font-medium text-slate-800">{{ $customer->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">DOB: {{ \Carbon\Carbon::parse($customer->dob)->format('d M Y') }}</p>
                </td>
                <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $customer->aadhar_number }}</td>
                <td class="px-5 py-4">
                    @if($customer->room)
                        <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-lg">
                            Room {{ $customer->room->room_number }}
                        </span>
                    @else
                        <span class="text-slate-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ $customer->check_in_purpose }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($customer->check_in_date)->format('d M Y') }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ $customer->number_of_days }}d</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('customers.show',$customer) }}"
                           class="flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-eye text-[11px]"></i> View
                        </a>
                        <form action="{{ route('customers.destroy',$customer) }}" method="POST"
                              onsubmit="return confirm('Delete this customer record permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="flex items-center gap-1.5 bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-5 py-16 text-center">
                    <i class="fa-solid fa-user-slash text-3xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">No customers checked in yet</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
