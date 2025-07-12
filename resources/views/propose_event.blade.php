@extends('layouts.app')

@section('content')
    <style>
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23000000' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 2.5rem;
        }
    </style>

    <div class="flex items-center justify-center min-h-[80vh] py-8">
        <div class="glassmorphism p-8 rounded-2xl shadow-lg w-full max-w-xl border border-white border-opacity-20">
            {{-- Back Button --}}
            @if (url()->previous() != url()->current())
                <div class="mb-4">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center text-sm font-medium text-white hover:text-gray-300 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                        Back
                    </a>
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-6 text-center text-white">Create New Event</h2>

            @if ($errors->any())
                <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-500 bg-opacity-10 border border-green-500 text-white px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('events.propose') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-white mb-2">Event Title</label>
                    <input type="text" name="title" id="title" placeholder="Event Title"
                        value="{{ old('title') }}"
                         required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                    <textarea name="description" id="description" placeholder="Description" rows="4" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white resize-y">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="start_datetime" class="block text-sm font-medium text-white mb-2">Start Date & Time</label>
                    <input type="datetime-local" name="start_datetime" id="start_datetime"
                        value="{{ old('start_datetime') }}" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="end_datetime" class="block text-sm font-medium text-white mb-2">End Date & Time</label>
                    <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime') }}"
                        required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-white mb-2">Location</label>
                    <input type="text" name="location" id="location" placeholder="Location"
                        value="{{ old('location') }}" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="area" class="block text-sm font-medium text-white mb-2">Area</label>
                    <select name="area" id="area" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-white">
                        <option value="" disabled selected class="text-gray-500">Select Area</option>
                        @foreach (\App\Models\EventRequest::getAreas() as $area)
                            <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}
                                class="text-black">
                                {{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-white mb-2">Category</label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-white">
                        <option value="" disabled selected class="text-gray-500">Select Category</option>
                        @foreach (\App\Models\EventRequest::getCategories() as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}
                                class="text-black">
                                {{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="event_type" class="block text-sm font-medium text-white mb-2">Type of Event</label>
                    <select name="event_type" id="event_type" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-white">
                        <option value="" disabled selected class="text-gray-500">Select Event Type</option>
                        @foreach (\App\Models\EventRequest::getEventTypes() as $eventType)
                            <option value="{{ $eventType }}" {{ old('event_type') == $eventType ? 'selected' : '' }}
                                class="text-black">
                                {{ $eventType }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="format" class="block text-sm font-medium text-white mb-2">Format</label>
                    <select name="format" id="format" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-white">
                        <option value="" disabled selected class="text-gray-500">Select Format</option>
                        @foreach (\App\Models\EventRequest::getFormats() as $format)
                            <option value="{{ $format }}" {{ old('format') == $format ? 'selected' : '' }}
                                class="text-black">
                                {{ $format }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-white mb-2">Fee</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="radio" name="is_free" id="free-entry" value="1"
                                class="form-radio h-4 w-4 text-black border-white focus:ring-white focus:ring-offset-0"
                                {{ old('is_free', 1) == 1 ? 'checked' : '' }}>
                            <label for="free-entry" class="ml-2 text-white">Free Entry</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="is_free" id="paid-entry" value="0"
                                class="form-radio h-4 w-4 text-black border-white focus:ring-white focus:ring-offset-0"
                                {{ old('is_free') == 0 ? 'checked' : '' }}>
                            <label for="paid-entry" class="ml-2 text-white">Paid Entry</label>
                        </div>
                    </div>
                </div>

                <div id="price_group" style="display: none;">
                    <label for="price" class="block text-sm font-medium text-white mb-2">Price</label>
                    <input type="number" name="price" id="price" placeholder="Price" step="0.01"
                        value="{{ old('price', 0) }}"
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="requester_email" class="block text-sm font-medium text-white mb-2">Your Email</label>
                    <input type="email" name="requester_email" id="requester_email" placeholder="Your Email"
                        value="{{ old('requester_email') }}" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="requester_phone" class="block text-sm font-medium text-white mb-2">Your Phone</label>
                    <input type="tel" name="requester_phone" id="requester_phone" placeholder="Your Phone Number"
                        required
                        value="{{ old('requester_phone') }}"
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="reference_link" class="block text-sm font-medium text-white mb-2">Reference Link
                        (Optional)</label>
                    <input type="url" name="reference_link" id="reference_link"
                        placeholder="https://example.com/event-info" value="{{ old('reference_link') }}"
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-white mb-2">Event Image</label>
                    <input type="file" name="image" id="image" accept="image/*" required
                        class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white"
                        onchange="previewImage(event)">
                    <div id="image-preview" class="mt-3"></div>
                    <button type="button" id="remove-image-btn"
                        class="mt-2 hidden bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-all"
                        onclick="removeImage()">Remove Image</button>
                </div>
                <script>
                    function previewImage(event) {
                        const preview = document.getElementById('image-preview');
                        const removeBtn = document.getElementById('remove-image-btn');
                        preview.innerHTML = '';
                        const file = event.target.files[0];
                        if (file) {
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.className = 'max-h-48 rounded-lg border mt-2';
                            img.onload = function() {
                                URL.revokeObjectURL(img.src);
                            }
                            preview.appendChild(img);
                            removeBtn.classList.remove('hidden');
                        } else {
                            removeBtn.classList.add('hidden');
                        }
                    }

                    function removeImage() {
                        const input = document.getElementById('image');
                        const preview = document.getElementById('image-preview');
                        const removeBtn = document.getElementById('remove-image-btn');
                        input.value = '';
                        preview.innerHTML = '';
                        removeBtn.classList.add('hidden');
                    }
                </script>

                <button type="submit"
                    class="w-full bg-white text-black font-medium py-3 rounded-full hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl !mt-6">
                    Submit Event Request
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Price Field Toggle ---
            const freeEntryRadio = document.getElementById('free-entry');
            const paidEntryRadio = document.getElementById('paid-entry');
            const priceGroup = document.getElementById('price_group');
            const priceInput = document.getElementById('price');

            function togglePriceField() {
                if (paidEntryRadio && paidEntryRadio.checked) {
                    priceGroup.style.display = 'block';
                    priceInput.setAttribute('required', 'required');
                } else {
                    priceGroup.style.display = 'none';
                    priceInput.removeAttribute('required');
                    priceInput.value = 0;
                }
            }

            if (freeEntryRadio) freeEntryRadio.addEventListener('change', togglePriceField);
            if (paidEntryRadio) paidEntryRadio.addEventListener('change', togglePriceField);

            // Initial call to set state on page load
            togglePriceField();
        });
    </script>
@endsection
