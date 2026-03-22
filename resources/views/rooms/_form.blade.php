<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
            Room Number <span class="text-red-400">*</span>
        </label>
        <input type="text" name="room_number"
               value="{{ old('room_number') }}"
               required placeholder="101"
               class="w-full border {{ $errors->has('room_number') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
        @error('room_number')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
            Floor Number <span class="text-slate-400 normal-case font-normal">(optional)</span>
        </label>
        <input type="number" name="floor"
               value="{{ old('floor') }}"
               placeholder="1" min="0"
               class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
    </div>
</div>

<div class="mb-4">
    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
        Room Description <span class="text-red-400">*</span>
    </label>
    <input type="text" name="description"
           value="{{ old('description') }}"
           required placeholder="e.g. AC Deluxe, Family Friendly, Suite..."
           class="w-full border {{ $errors->has('description') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
    @error('description')
        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
            Capacity (persons) <span class="text-red-400">*</span>
        </label>
        <input type="number" name="capacity"
               value="{{ old('capacity', 2) }}"
               required min="1"
               class="w-full border {{ $errors->has('capacity') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
        @error('capacity')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
            Price per Day (₹) <span class="text-red-400">*</span>
        </label>
        <input type="number" name="price_per_day"
               value="{{ old('price_per_day') }}"
               required min="0" step="0.01" placeholder="2500"
               class="w-full border {{ $errors->has('price_per_day') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
        @error('price_per_day')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>
</div>
