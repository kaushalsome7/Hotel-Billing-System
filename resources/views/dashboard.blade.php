@extends('layouts.app')
@section('title','Dashboard')

@section('content')

{{-- Page header --}}
<div class="mb-7">
    <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Dashboard</h2>
    <p class="text-slate-400 text-sm mt-1">Overview of your hotel management system</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-4 gap-5 mb-6">
    @php
        $stats = [
            ['label'=>'Customers',   'value'=>$totalCustomers, 'icon'=>'fa-user-group',      'bg'=>'bg-blue-900',   'ring'=>'ring-blue-800'],
            ['label'=>'Rooms',       'value'=>$totalRooms,     'icon'=>'fa-bed',              'bg'=>'bg-amber-700',  'ring'=>'ring-amber-600'],
            ['label'=>'Menu Items',  'value'=>$totalMenuItems, 'icon'=>'fa-utensils',         'bg'=>'bg-emerald-800','ring'=>'ring-emerald-700'],
            ['label'=>'Services',    'value'=>$totalServices,  'icon'=>'fa-bell-concierge',   'bg'=>'bg-red-800',    'ring'=>'ring-red-700'],
        ];
    @endphp

    @foreach($stats as $s)
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 {{ $s['bg'] }} rounded-xl flex items-center justify-center mb-4 ring-4 {{ $s['ring'] }} ring-opacity-20">
            <i class="fa-solid {{ $s['icon'] }} text-white text-base"></i>
        </div>
        <div class="text-3xl font-semibold text-slate-800">{{ $s['value'] }}</div>
        <div class="text-slate-400 text-sm mt-1">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Info card --}}
<div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm">
    <h3 class="text-base font-semibold text-slate-800 mb-3">Welcome to BillifyStay</h3>
    <p class="text-slate-500 text-sm leading-relaxed max-w-2xl">
        Manage your customers, rooms, restaurant menu, and room services all in one place.
        Use the sidebar to navigate between sections. When ready, head to Billing to generate
        a comprehensive bill for any customer.
    </p>
</div>

@endsection
