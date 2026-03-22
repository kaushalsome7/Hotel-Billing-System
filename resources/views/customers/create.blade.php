@extends('layouts.app')
@section('title','Check In Customer')

@section('content')

<div class="flex items-start justify-between mb-7">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight">Check In Customer</h2>
        <p class="text-slate-400 text-sm mt-1">Fill in all details to register a new customer</p>
    </div>
    <a href="{{ route('customers.index') }}"
       class="flex items-center gap-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back
    </a>
</div>

@if($errors->any())
    <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        Please fix the errors below before submitting.
    </div>
@endif

<form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="grid grid-cols-3 gap-5 items-start">

    {{-- ── Left 2 cols: form fields ── --}}
    <div class="col-span-2 space-y-5">

        {{-- Personal Details --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-5 pb-4 border-b border-slate-100">
                Personal Details
            </p>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Full Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Rahul Sharma"
                           class="w-full border {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Date of Birth <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="dob" value="{{ old('dob') }}" required max="{{ date('Y-m-d') }}"
                           class="w-full border {{ $errors->has('dob') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    @error('dob')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Aadhar Number <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="aadhar_number" value="{{ old('aadhar_number') }}" required
                           placeholder="XXXX XXXX XXXX" maxlength="14" id="aadharInput"
                           class="w-full border {{ $errors->has('aadhar_number') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 font-mono transition-all">
                    @error('aadhar_number')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Purpose of Visit <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="check_in_purpose" value="{{ old('check_in_purpose') }}" required
                           placeholder="e.g. Business, Vacation, Medical..."
                           class="w-full border {{ $errors->has('check_in_purpose') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    @error('check_in_purpose')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                    Aadhar Card Image <span class="text-red-400">*</span>
                </label>
                <input type="file" name="aadhar_image" id="aadharFile" required accept="image/*"
                       onchange="previewAadhar(this)"
                       class="w-full border {{ $errors->has('aadhar_image') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }} rounded-xl px-4 py-2.5 text-sm text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-900 file:text-white transition-all">
                @error('aadhar_image')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                <div id="aadharPreview" class="mt-3 hidden">
                    <img id="aadharPreviewImg" src="" alt="Aadhar" class="h-28 rounded-xl border border-slate-200 object-cover">
                </div>
            </div>
        </div>

        {{-- Stay Details --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-5 pb-4 border-b border-slate-100">
                Stay Details
            </p>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Check-in Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" required
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    @error('check_in_date')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                        Number of Days <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="number_of_days" value="{{ old('number_of_days',1) }}" required min="1" max="365"
                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    @error('number_of_days')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">
                    Room Assignment
                    <span class="text-slate-400 normal-case tracking-normal font-normal ml-1">(optional — customer may only use restaurant)</span>
                </label>
                <select name="room_id" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-800 transition-all">
                    <option value="">— No Room (Restaurant / Service Only) —</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id')==$room->id ? 'selected' : '' }}>
                            Room {{ $room->room_number }}
                            (Floor {{ $room->floor ?? '—' }})
                            — {{ $room->description }}
                            — Capacity: {{ $room->capacity }}
                            — ₹{{ number_format($room->price_per_day,2) }}/day
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    {{-- ── Right col: Webcam ── --}}
    <div class="col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 pb-4 border-b border-slate-100">
                Customer Photo
            </p>
            <p class="text-xs text-slate-400 mb-4">Capture the customer's photo using the camera.</p>

            {{-- Camera --}}
            <div class="bg-black rounded-xl overflow-hidden aspect-4/3 flex items-center justify-center mb-3 relative" id="cameraWrapper">
                <video id="video" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                <canvas id="canvas" class="hidden w-full h-full object-cover"></canvas>
                <img id="photoPreview" src="" alt="" class="w-full h-full object-cover hidden">
                <div id="cameraPlaceholder" class="text-center text-white/40">
                    <i class="fa-solid fa-camera text-4xl block mb-2"></i>
                    <span class="text-xs">Camera not started</span>
                </div>
            </div>

            <div class="flex gap-2 justify-center">
                <button type="button" id="startBtn" onclick="startCamera()"
                        class="flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-video text-[11px]"></i> Start
                </button>
                <button type="button" id="captureBtn" onclick="capturePhoto()"
                        class="hidden items-center gap-1.5 bg-slate-900 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-camera text-[11px]"></i> Capture
                </button>
                <button type="button" id="retakeBtn" onclick="retakePhoto()"
                        class="hidden items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i> Retake
                </button>
            </div>

            <input type="hidden" name="photo_data" id="photoData">
            @error('photo_data')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        </div>
    </div>

</div>

{{-- Form footer --}}
<div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-200">
    <a href="{{ route('customers.index') }}"
       class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
        Cancel
    </a>
    <button type="submit"
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
        <i class="fa-solid fa-user-check text-xs"></i> Complete Check-in
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
let stream = null;
function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(s => {
            stream = s;
            const v = document.getElementById('video');
            v.srcObject = stream;
            v.classList.remove('hidden');
            document.getElementById('cameraPlaceholder').classList.add('hidden');
            document.getElementById('startBtn').classList.add('hidden');
            document.getElementById('captureBtn').classList.remove('hidden');
        })
        .catch(e => alert('Camera error: ' + e.message));
}
function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById('photoData').value = dataUrl;
    const prev = document.getElementById('photoPreview');
    prev.src = dataUrl; prev.classList.remove('hidden');
    video.classList.add('hidden');
    if (stream) stream.getTracks().forEach(t => t.stop());
    document.getElementById('captureBtn').classList.add('hidden');
    document.getElementById('retakeBtn').classList.remove('hidden');
}
function retakePhoto() {
    document.getElementById('photoPreview').classList.add('hidden');
    document.getElementById('photoData').value = '';
    document.getElementById('retakeBtn').classList.add('hidden');
    document.getElementById('startBtn').classList.remove('hidden');
    document.getElementById('cameraPlaceholder').classList.remove('hidden');
}
function previewAadhar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('aadharPreviewImg').src = e.target.result;
            document.getElementById('aadharPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
document.getElementById('aadharInput').addEventListener('input', function() {
    let v = this.value.replace(/\D/g,'').substring(0,12);
    this.value = v.replace(/(.{4})/g,'$1 ').trim();
});
</script>
@endpush
