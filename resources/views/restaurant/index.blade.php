@extends('layouts.app')
@section('title','Restaurant')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Restaurant</h2>
        <p class="text-slate-400 text-sm mt-1">Manage restaurant menu items and pricing</p>
    </div>
    <button onclick="openModal('addModal')"
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
        <i class="fa-solid fa-plus text-xs"></i> Add Menu Item
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b border-slate-100">
                @foreach(['#','Item Name','Food Quantity','Price','Actions'] as $h)
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($menuItems as $item)
            <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                <td class="px-5 py-4 text-xs text-slate-400">{{ $loop->iteration }}</td>
                <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ $item->name }}</td>
                <td class="px-5 py-4">
                    <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-lg">{{ $item->quantity_type }}</span>
                </td>
                <td class="px-5 py-4 text-sm font-medium text-slate-800">₹{{ number_format($item->price,2) }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="openEdit({{ $item->id }},'{{ addslashes($item->name) }}','{{ addslashes($item->quantity_type) }}',{{ $item->price }})"
                                class="flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen text-[11px]"></i> Edit
                        </button>
                        <form action="{{ route('restaurant.destroy',$item) }}" method="POST"
                              onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-16 text-center">
                    <i class="fa-solid fa-utensils text-3xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">No menu items added yet</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@php $modalInputClass = "w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all"; @endphp

{{-- Add Modal --}}
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('addModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Add Menu Item</h3>
            <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('restaurant.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Item Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Butter Chicken"
                       class="{{ $modalInputClass }} {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Food Quantity <span class="text-red-400">*</span></label>
                    <select name="quantity_type" required class="{{ $modalInputClass }}">
                        <option value="">— Select —</option>
                        @foreach(['Per Plate','Per Piece','Per Bowl','Per Glass','Per Cup'] as $qt)
                            <option value="{{ $qt }}" {{ old('quantity_type')===$qt ? 'selected':'' }}>{{ $qt }}</option>
                        @endforeach
                    </select>
                    @error('quantity_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01" placeholder="350"
                           class="{{ $modalInputClass }}">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('addModal')" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors">Save Item</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('editModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Edit Menu Item</h3>
            <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="editForm" action="" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Item Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="edit_name" required class="{{ $modalInputClass }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Food Quantity</label>
                    <select name="quantity_type" id="edit_qty" class="{{ $modalInputClass }}">
                        @foreach(['Per Plate','Per Piece','Per Bowl','Per Glass','Per Cup'] as $qt)
                            <option value="{{ $qt }}">{{ $qt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Price (₹)</label>
                    <input type="number" name="price" id="edit_price" min="0" step="0.01" class="{{ $modalInputClass }}">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('editModal')" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors">Update Item</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any() || old('name'))
<script>document.addEventListener('DOMContentLoaded',()=>openModal('addModal'));</script>
@endif
@endsection

@push('scripts')
<script>
function openModal(id)  { const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function openEdit(id,name,qty,price) {
    document.getElementById('editForm').action='/restaurant/'+id;
    document.getElementById('edit_name').value=name;
    document.getElementById('edit_qty').value=qty;
    document.getElementById('edit_price').value=price;
    openModal('editModal');
}
</script>
@endpush
