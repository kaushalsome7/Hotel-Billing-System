<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BillifyStay') — Billing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar:  '#0f172a',
                        'sidebar-hover':  '#1e293b',
                        'sidebar-active': '#1e3a5f',
                        gold:      '#d4a84b',
                        'gold-dark':'#b8923f',
                        cream:    '#f8f6f1',
                    },
                    fontFamily: {
                        sans:    ['DM Sans', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(212,168,75,0.18);
            border-color: #d4a84b !important;
        }
        @media print { aside,.no-print{display:none!important} main{margin-left:0!important} }
    </style>
    @stack('styles')
</head>
<body class="bg-cream min-h-screen flex">

<aside class="w-64 bg-sidebar flex flex-col fixed inset-y-0 left-0 z-50 overflow-y-auto">
    <div class="px-6 py-7 border-b border-white/5 flex shrink-0">
        <h1 class="text-gold text-xl font-bold tracking-wide" style="font-family:'Playfair Display',serif">BillifyStay</h1>
        <span class="text-white/30 text-[10px] tracking-widest uppercase mt-1 block font-light"><br>v1.0</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @php
            $navItems = [
                ['route'=>'dashboard',         'icon'=>'fa-table-cells-large','label'=>'Dashboard',     'match'=>'dashboard'],
                ['route'=>'customers.index',   'icon'=>'fa-user-group',       'label'=>'Customers',     'match'=>'customers.*'],
                ['route'=>'rooms.index',        'icon'=>'fa-bed',              'label'=>'Rooms',         'match'=>'rooms.*'],
                ['route'=>'restaurant.index',   'icon'=>'fa-utensils',         'label'=>'Restaurant',    'match'=>'restaurant.*'],
                ['route'=>'room-services.index','icon'=>'fa-bell-concierge',   'label'=>'Room Services', 'match'=>'room-services.*'],
                ['route'=>'billing.index',      'icon'=>'fa-receipt',          'label'=>'Billing',       'match'=>'billing.*'],
            ];
        @endphp
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-all duration-150
                      {{ request()->routeIs($item['match'])
                         ? 'bg-sidebar-active text-gold font-medium'
                         : 'text-white/50 hover:bg-sidebar-hover hover:text-white/80' }}">
                <i class="fa-solid {{ $item['icon'] }} w-4 text-center text-[13px] flex shrink-0"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/5 px-4 py-3 items-center gap-3 flex shrink-0">
        <div class="w-8 h-8 rounded-full bg-sidebar-active justify-content-center text-gold text-xs font-semibold shrink-0 flex items-center justify-center">
            {{ strtoupper(substr(auth()->user()->name,0,2)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-white/80 text-xs font-medium truncate">{{ auth()->user()->name }}</p>
            <p class="text-white/30 text-[10px]">Receptionist</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-white/25 hover:text-red-400 transition-colors text-sm" title="Logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </div>
    <div class="px-6 py-3 border-t border-white/5 flex shrink-0">
        <p class="text-white/15 text-[10px] tracking-wider">BillifyStay v1.0</p>
    </div>
</aside>

<main class="ml-64 flex-1 min-h-screen p-8">
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 text-red-700 border border-red-200 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
        </div>
    @endif
    @yield('content')
</main>

@stack('scripts')
</body>
</html>

