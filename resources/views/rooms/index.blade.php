@extends('layouts.app')
@section('title','Rooms')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Rooms</h2>
        <p class="text-slate-400 text-sm mt-1">Manage hotel rooms and their pricing</p>
    </div>
    <button onclick="openModal('addModal')"
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
        <i class="fa-solid fa-plus text-xs"></i> Add Room
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b border-slate-100">
                @foreach(['Room #','Floor','Description','Capacity','Price / Day','Actions'] as $h)
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($rooms as $room)
            <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                <td class="px-5 py-4 text-sm font-semibold text-slate-800">{{ $room->room_number }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ $room->floor ?? '—' }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ $room->description }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ $room->capacity }} person(s)</td>
                <td class="px-5 py-4 text-sm font-medium text-slate-800">₹{{ number_format($room->price_per_day,2) }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="openEdit({{ $room->id }},'{{ $room->room_number }}','{{ $room->floor }}','{{ addslashes($room->description) }}',{{ $room->capacity }},{{ $room->price_per_day }})"
                                class="flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen text-[11px]"></i> Edit
                        </button>
                        <form action="{{ route('rooms.destroy',$room) }}" method="POST"
                              onsubmit="return confirm('Delete Room {{ $room->room_number }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center">
                    <i class="fa-solid fa-bed text-3xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">No rooms added yet</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── Add Modal ── --}}
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('addModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Add Room</h3>
            <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" onclick="closeModal('addModal')"
                        class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors">
                    Save Room
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Edit Modal ── --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('editModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Edit Room</h3>
            <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="editForm" action="" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Room Number <span class="text-red-400">*</span></label>
                    <input type="text" name="room_number" id="edit_room_number" required
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Floor (optional)</label>
                    <input type="number" name="floor" id="edit_floor" min="0"
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Description <span class="text-red-400">*</span></label>
                <input type="text" name="description" id="edit_description" required
                       class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Capacity <span class="text-red-400">*</span></label>
                    <input type="number" name="capacity" id="edit_capacity" min="1" required
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Price / Day (₹) <span class="text-red-400">*</span></label>
                    <input type="number" name="price_per_day" id="edit_price" min="0" step="0.01" required
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" onclick="closeModal('editModal')"
                        class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors">
                    Update Room
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->any() || old('room_number'))
<script>document.addEventListener('DOMContentLoaded',()=>openModal('addModal'));</script>
@endif
@endsection

@push('scripts')
<script>
function openModal(id)  { const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function openEdit(id,num,floor,desc,cap,price) {
    document.getElementById('editForm').action='/rooms/'+id;
    document.getElementById('edit_room_number').value=num;
    document.getElementById('edit_floor').value=floor;
    document.getElementById('edit_description').value=desc;
    document.getElementById('edit_capacity').value=cap;
    document.getElementById('edit_price').value=price;
    openModal('editModal');
}
</script>
@endpush
