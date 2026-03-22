@extends('layouts.app')
@section('title','Customer Details')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Customer Details</h2>
        <p class="text-slate-400 text-sm mt-1">Viewing record for {{ $customer->name }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('billing.show',$customer) }}"
           class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-file-invoice text-xs"></i> Generate Bill
        </a>
        <a href="{{ route('customers.index') }}"
           class="flex items-center gap-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-3 gap-5 items-start">
    <div class="col-span-2 space-y-5">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-5 pb-4 border-b border-slate-100">Personal Information</p>
            <div class="grid grid-cols-2 gap-6">
                @foreach([
                    ['Full Name',       $customer->name],
                    ['Date of Birth',   \Carbon\Carbon::parse($customer->dob)->format('d M Y')],
                    ['Aadhar Number',   $customer->aadhar_number],
                    ['Purpose of Visit',$customer->check_in_purpose],
                ] as [$label,$value])
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">{{ $label }}</p>
                    <p class="text-sm font-medium text-slate-800 mt-1 {{ $label==='Aadhar Number' ? 'font-mono' : '' }}">{{ $value }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-5 pb-4 border-b border-slate-100">Stay Information</p>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Check-in Date</p>
                    <p class="text-sm font-medium text-slate-800 mt-1">{{ \Carbon\Carbon::parse($customer->check_in_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Number of Days</p>
                    <p class="text-sm font-medium text-slate-800 mt-1">{{ $customer->number_of_days }} day(s)</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Expected Check-out</p>
                    <p class="text-sm font-medium text-slate-800 mt-1">
                        {{ \Carbon\Carbon::parse($customer->check_in_date)->addDays($customer->number_of_days)->format('d M Y') }}
                    </p>
                </div>
                <div class="col-span-3">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Room</p>
                    <p class="text-sm font-medium text-slate-800 mt-1">
                        @if($customer->room)
                            <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-lg">
                                Room {{ $customer->room->room_number }} — {{ $customer->room->description }}
                            </span>
                        @else
                            <span class="text-slate-400">No room assigned</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if($customer->aadhar_image_path)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 pb-4 border-b border-slate-100">Aadhar Card</p>
            <img src="{{ asset('storage/'.$customer->aadhar_image_path) }}"
                 class="max-w-full rounded-xl border border-slate-100" alt="Aadhar Card">
        </div>
        @endif
    </div>

    {{-- Photo --}}
    <div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-5 pb-4 border-b border-slate-100">Customer Photo</p>
            @if($customer->photo_path)
                <img src="{{ asset('storage/'.$customer->photo_path) }}"
                     class="w-full rounded-xl border border-slate-100 object-cover" alt="{{ $customer->name }}">
            @else
                <div class="w-32 h-32 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-4xl font-semibold mx-auto">
                    {{ strtoupper(substr($customer->name,0,2)) }}
                </div>
            @endif
            <p class="font-semibold text-slate-800 mt-4">{{ $customer->name }}</p>
            <p class="text-xs text-slate-400 mt-1">Checked in {{ \Carbon\Carbon::parse($customer->check_in_date)->diffForHumans() }}</p>
        </div>
    </div>
</div>
@endsection
