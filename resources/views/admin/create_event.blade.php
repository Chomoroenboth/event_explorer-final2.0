@extends('layouts.app_admin')

@section('page-title', 'Create Event')
@section('page-description', 'Post a new event directly')

@section('content')
    <div class="fade-in">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.events') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    Create New Event
                </h2>
                <p class="text-sm text-gray-600">Create and publish an event directly as admin</p>
            </div>

            <div class="p-6">
                <!-- Alerts -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <h4 class="font-medium mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" name="title" id="title" 
                                       value="{{ old('title') }}" 
                                       class="form-input" required>
                            </div>

                            <div>
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4" 
                                          class="form-input" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_datetime" class="form-label">Start Date & Time</label>
                                    <input type="datetime-local" name="start_datetime" id="start_datetime"
                                           value="{{ old('start_datetime') }}" 
                                           class="form-input" required>
                                </div>
                                <div>
                                    <label for="end_datetime" class="form-label">End Date & Time</label>
                                    <input type="datetime-local" name="end_datetime" id="end_datetime"
                                           value="{{ old('end_datetime') }}" 
                                           class="form-input" required>
                                </div>
                            </div>

                            <div>
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" 
                                       value="{{ old('location') }}" 
                                       class="form-input" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="area" class="form-label">Area</label>
                                    <select name="area" id="area" class="form-input" required>
                                        <option value="">Select Area</option>
                                        @foreach (\App\Models\EventRequest::getAreas() as $area)
                                            <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>
                                                {{ $area }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-input" required>
                                        <option value="">Select Category</option>
                                        @foreach (\App\Models\EventRequest::getCategories() as $category)
                                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="event_type" class="form-label">Event Type</label>
                                    <select name="event_type" id="event_type" class="form-input" required>
                                        <option value="">Select Type</option>
                                        @foreach (\App\Models\EventRequest::getEventTypes() as $eventType)
                                            <option value="{{ $eventType }}" {{ old('event_type') == $eventType ? 'selected' : '' }}>
                                                {{ $eventType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="format" class="form-label">Format</label>
                                    <select name="format" id="format" class="form-input" required>
                                        <option value="">Select Format</option>
                                        @foreach (\App\Models\EventRequest::getFormats() as $format)
                                            <option value="{{ $format }}" {{ old('format') == $format ? 'selected' : '' }}>
                                                {{ $format }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Entry Fee</label>
                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_free" value="1" 
                                               {{ old('is_free', '1') == '1' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Free Entry</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_free" value="0" 
                                               {{ old('is_free') == '0' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Paid Entry</span>
                                    </label>
                                </div>
                            </div>

                            <div id="price_group" style="display: none;">
                                <label for="price" class="form-label">Price ($)</label>
                                <input type="number" name="price" id="price" step="0.01"
                                       value="{{ old('price') }}" 
                                       class="form-input">
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="requester_email" class="form-label">Contact Email</label>
                                    <input type="email" name="requester_email" id="requester_email" 
                                           value="{{ old('requester_email', auth('admin')->user()->email) }}" 
                                           class="form-input" required>
                                </div>
                                <div>
                                    <label for="requester_phone" class="form-label">Contact Phone</label>
                                    <input type="tel" name="requester_phone" id="requester_phone"
                                           value="{{ old('requester_phone') }}" 
                                           class="form-input">
                                </div>
                            </div>

                            <div>
                                <label for="reference_link" class="form-label">Reference Link (Optional)</label>
                                <input type="url" name="reference_link" id="reference_link"
                                       value="{{ old('reference_link') }}" 
                                       class="form-input">
                            </div>

                            <div>
                                <label for="image" class="form-label">Event Image</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                       class="form-input" onchange="previewImage(event)" required>
                                
                                <div id="image-preview" class="mt-3"></div>
                                
                                <button type="button" id="remove-image-btn"
                                        class="mt-2 hidden px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors"
                                        onclick="removeImage()">
                                    Remove Image
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create & Publish Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paidEntryRadio = document.querySelector('input[name="is_free"][value="0"]');
            const freeEntryRadio = document.querySelector('input[name="is_free"][value="1"]');
            const priceGroup = document.getElementById('price_group');
            const priceInput = document.getElementById('price');

            function togglePrice() {
                if (paidEntryRadio.checked) {
                    priceGroup.style.display = 'block';
                    priceInput.required = true;
                } else {
                    priceGroup.style.display = 'none';
                    priceInput.required = false;
                    priceInput.value = 0;
                }
            }

            paidEntryRadio.addEventListener('change', togglePrice);
            freeEntryRadio.addEventListener('change', togglePrice);
            togglePrice();
        });

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            const removeBtn = document.getElementById('remove-image-btn');
            
            preview.innerHTML = '';
            
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'max-h-48 rounded-lg border';
                img.onload = function() {
                    URL.revokeObjectURL(img.src);
                }
                preview.appendChild(img);
                removeBtn.classList.remove('hidden');
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').innerHTML = '';
            document.getElementById('remove-image-btn').classList.add('hidden');
        }
    </script>
@endsection