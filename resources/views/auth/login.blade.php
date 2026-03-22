@extends('layouts.auth')
@section('title','Login')

@section('content')
<div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl shadow-black/40 p-10">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <h1 class="text-gold text-3xl font-bold tracking-wide mb-1" style="font-family:'Playfair Display',serif">
                BillifyStay
            </h1>
            <p class="text-slate-400 text-xs tracking-widest uppercase font-light">Billing Management System</p>
        </div>

        @if($errors->any())
            <div class="mb-5 flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 rounded-xl px-4 py-3 text-sm">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                    Email Address
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="Enter email" required autofocus
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 bg-slate-50 transition-all duration-150 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
                @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                    Password
                </label>
                <input type="password" name="password"
                       placeholder="Enter password" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 bg-slate-50 transition-all duration-150 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
                @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2.5">
                <input type="checkbox" name="remember" id="remember"
                       class="w-4 h-4 rounded accent-yellow-600 cursor-pointer">
                <label for="remember" class="text-sm text-slate-500 cursor-pointer">Remember me</label>
            </div>

            <button type="submit"
                    class="w-full bg-sidebar hover:bg-sidebar-hover text-white font-medium py-3 rounded-xl text-sm transition-all duration-150 flex items-center justify-center gap-2 mt-2"
                    style="--tw-bg-opacity:1">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
            </button>
        </form>
    </div>

    <p class="text-center text-white/20 text-xs mt-6">BillifyStay - Hotel Billing Management System created by SIT-BCA students</p>
</div>
@endsection
