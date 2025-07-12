@props(['event' => \App\Models\EventRequest::class])

@php
    $imageUrl = $event->image ? asset('storage/' . $event->image) : asset('placeholder.svg');
@endphp

<div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 transform hover:-translate-y-1">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
        <!-- Enhanced Image Section -->
        <div class="lg:col-span-1 relative">
            <div class="aspect-video lg:aspect-square w-full bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden relative">
                <img src="{{ $imageUrl }}" alt="Event Image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <!-- Gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <!-- Event type badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold rounded-full shadow-lg">
                        {{ $event->event_type }}
                    </span>
                </div>
                <!-- Admin Posted Badge -->
                @if(is_null($event->requested_by))
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-blue-500/90 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-lg">
                            Admin Posted
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Enhanced Content Section -->
        <div class="lg:col-span-3 p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-start">
                <div class="flex-1 pr-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                        {{ $event->title }}
                    </h3>
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">{{ $event->location }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 shadow-sm">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Published
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $event->is_free ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }} shadow-sm">
                        {{ $event->is_free ? 'Free' : '$' . number_format($event->price, 2) }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <p class="text-gray-600 line-clamp-2 text-sm leading-relaxed">{{ $event->description }}</p>

            <!-- Enhanced Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-3">
                    <!-- Date Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-100">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">Event Date</span>
                        </div>
                        <p class="text-gray-700 text-sm font-medium ml-11">
                            {{ date('M j, Y g:i A', strtotime($event->start_datetime)) }}
                        </p>
                        <p class="text-gray-500 text-xs ml-11">
                            to {{ date('M j, Y g:i A', strtotime($event->end_datetime)) }}
                        </p>
                    </div>
                    
                    <!-- Category Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-3 border border-purple-100">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">Category</span>
                        </div>
                        <p class="text-gray-700 text-sm font-medium ml-11">{{ $event->category }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-3">
                    <!-- Location & Format Card -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 border border-green-100">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">Location & Format</span>
                        </div>
                        <p class="text-gray-700 text-sm font-medium ml-11">{{ $event->area }}</p>
                        <p class="text-gray-500 text-xs ml-11">{{ $event->format }}</p>
                    </div>
                    
                    <!-- Contact Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-3 border border-orange-100">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">Contact</span>
                        </div>
                        <div class="ml-11 space-y-1">
                            <a href="mailto:{{ $event->requester_email }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium block transition-colors">
                                {{ $event->requester_email }}
                            </a>
                            @if ($event->requester_phone)
                                <a href="tel:{{ $event->requester_phone }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium block transition-colors">
                                    {{ $event->requester_phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($event->reference_link)
                <!-- Reference Link -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900 text-sm block">Reference Link</span>
                            <a href="{{ $event->reference_link }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors break-all">
                                {{ $event->reference_link }}
                            </a>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </div>
                </div>
            @endif

            <!-- Admin Actions -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-xs text-gray-500">
                            <span class="font-medium">Published:</span> {{ date('M j, Y g:i A', strtotime($event->updated_at)) }}
                        </div>
                        @if ($event->approved_by)
                            <div class="text-xs text-gray-500">
                                <span class="font-medium">By:</span> Admin {{ $event->approved_by }}
                            </div>
                        @endif
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="font-medium">{{ \App\Models\SavedEvent::where('event_id', $event->id)->count() }} saves</span>
                        </div>
                    </div>
                    
                    <!-- Improved Action Buttons -->
                    <div class="event-actions">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-edit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit</span>
                        </a>
                        
                        <form method="POST" action="{{ route('admin.events.delete', $event->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>