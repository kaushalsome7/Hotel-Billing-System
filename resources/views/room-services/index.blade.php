@extends('layouts.app')
@section('title','Room Services')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Room Services</h2>
        <p class="text-slate-400 text-sm mt-1">Manage available room service offerings</p>
    </div>
    <button onclick="openModal('addModal')"
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
        <i class="fa-solid fa-plus text-xs"></i> Add Service
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b border-slate-100">
                @foreach(['#','Service Name','Actions'] as $h)
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($services as $service)
            <tr class="hover:bg-slate-50/60 transition-colors duration-100">
                <td class="px-5 py-4 text-xs text-slate-400">{{ $loop->iteration }}</td>
                <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ $service->name }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="openEdit({{ $service->id }},'{{ addslashes($service->name) }}')"
                                class="flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen text-[11px]"></i> Edit
                        </button>
                        <form action="{{ route('room-services.destroy',$service) }}" method="POST"
                              onsubmit="return confirm('Delete this service?')">
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
                <td colspan="3" class="px-5 py-16 text-center">
                    <i class="fa-solid fa-bell-concierge text-3xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">No services added yet</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@php $ic = "w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all"; @endphp

{{-- Add Modal --}}
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('addModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Add Room Service</h3>
            <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('room-services.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Service Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="e.g. Laundry, Spa, Airport Pickup..."
                       class="{{ $ic }} {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}">
                @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('addModal')" class="border border-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-xl">Cancel</button>
                <button type="submit" class="bg-slate-900 text-white text-sm font-medium px-5 py-2 rounded-xl">Save Service</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeModal('editModal')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-7">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-slate-800">Edit Room Service</h3>
            <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="editForm" action="" method="POST">
            @csrf @method('PUT')
            <div class="mb-5">
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Service Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="edit_name" required class="{{ $ic }}">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('editModal')" class="border border-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-xl">Cancel</button>
                <button type="submit" class="bg-slate-900 text-white text-sm font-medium px-5 py-2 rounded-xl">Update</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any()||old('name'))
<script>document.addEventListener('DOMContentLoaded',()=>openModal('addModal'));</script>
@endif
@endsection

@push('scripts')
<script>
function openModal(id)  { const m=document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m=document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }
function openEdit(id,name) {
    document.getElementById('editForm').action='/room-services/'+id;
    document.getElementById('edit_name').value=name;
    openModal('editModal');
}
</script>
@endpush
