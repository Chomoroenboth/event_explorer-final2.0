@props(['eventRequest' => \App\Models\EventRequest::class])

@php
    $imageUrl = $eventRequest->image ? asset('storage/' . $eventRequest->image) : asset('placeholder.svg');
    $isUrgent = $eventRequest->created_at->diffInHours() < 24;
    $timeAgo = $eventRequest->created_at->diffForHumans();
@endphp

<div class="event-card group relative overflow-hidden">
    <!-- Urgent indicator -->
    @if($isUrgent)
        <div class="absolute top-4 right-4 z-10">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                <div class="w-2 h-2 bg-red-400 rounded-full mr-2 pulse"></div>
                New Request
            </span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Enhanced Image Section -->
        <div class="lg:col-span-2 relative">
            <div class="aspect-video lg:aspect-square w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden relative group">
                <img src="{{ $imageUrl }}" alt="Event Image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                
                <!-- Overlay with event type -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Event details overlay -->
                <div class="absolute bottom-4 left-4 right-4 transform translateY-full group-hover:translateY-0 transition-transform duration-300">
                    <div class="bg-white/90 backdrop-blur-sm rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-800">{{ $eventRequest->event_type }}</span>
                            <span class="text-xs text-gray-600">{{ $eventRequest->format }}</span>
                        </div>
                    </div>
                </div>

                <!-- Category badge -->
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold rounded-full shadow-lg">
                        {{ $eventRequest->category }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Enhanced Content Section -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Header with status -->
            <div class="flex justify-between items-start">
                <div class="flex-1 pr-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                        {{ $eventRequest->title }}
                    </h3>
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">{{ $eventRequest->location }} • {{ $eventRequest->area }}</span>
                        <span class="mx-2">•</span>
                        <span class="text-gray-400">{{ $timeAgo }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <span class="status-badge status-{{ $eventRequest->approval_status }}">
                        {{ ucfirst($eventRequest->approval_status) }}
                    </span>
                    @if($eventRequest->is_free)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            Free Event
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            ${{ number_format($eventRequest->price, 2) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-700 line-clamp-3 leading-relaxed">{{ $eventRequest->description }}</p>
            </div>

            <!-- Enhanced Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Date & Time Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">Event Schedule</span>
                                <span class="text-xs text-gray-500">Date and time details</span>
                            </div>
                        </div>
                        <div class="ml-13 space-y-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Start:</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($eventRequest->start_datetime)->format('M j, Y @ g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">End:</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($eventRequest->end_datetime)->format('M j, Y @ g:i A') }}</p>
                            </div>
                            <div class="pt-2 border-t border-blue-200">
                                <p class="text-xs text-blue-600 font-medium">
                                    Duration: {{ \Carbon\Carbon::parse($eventRequest->start_datetime)->diffInHours(\Carbon\Carbon::parse($eventRequest->end_datetime)) }} hours
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Details Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">Event Information</span>
                                <span class="text-xs text-gray-500">Category and type</span>
                            </div>
                        </div>
                        <div class="ml-13 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Category:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $eventRequest->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Type:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $eventRequest->event_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Format:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $eventRequest->format }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Contact Information Card -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">Contact Details</span>
                                <span class="text-xs text-gray-500">Organizer information</span>
                            </div>
                        </div>
                        <div class="ml-13 space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Email</p>
                                <a href="mailto:{{ $eventRequest->requester_email }}" 
                                   class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors break-all">
                                    {{ $eventRequest->requester_email }}
                                </a>
                            </div>
                            @if ($eventRequest->requester_phone)
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Phone</p>
                                    <a href="tel:{{ $eventRequest->requester_phone }}" 
                                       class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors">
                                        {{ $eventRequest->requester_phone }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Pricing Card -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-100">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">Pricing Information</span>
                                <span class="text-xs text-gray-500">Entry fee details</span>
                            </div>
                        </div>
                        <div class="ml-13">
                            @if($eventRequest->is_free)
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-green-600">FREE</span>
                                    <div class="ml-3">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">No entry fee required</p>
                            @else
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-orange-600">${{ number_format($eventRequest->price, 2) }}</span>
                                    <span class="text-sm text-gray-500 ml-2">per person</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Entry fee required</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($eventRequest->reference_link)
                <!-- Reference Link Section -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900 text-sm block">Reference Material</span>
                            <span class="text-xs text-gray-500">Additional information link</span>
                        </div>
                        <a href="{{ $eventRequest->reference_link }}" target="_blank" 
                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                            <span>View Link</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="ml-13">
                        <p class="text-sm text-gray-600 font-mono bg-white p-2 rounded border break-all">
                            {{ $eventRequest->reference_link }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Enhanced Action Buttons -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Review Actions</h4>
                        <p class="text-sm text-gray-600">Choose how to handle this event request</p>
                    </div>
                    <div class="text-xs text-gray-500">
                        <span class="font-medium">Submitted:</span> {{ $eventRequest->created_at->format('M j, Y @ g:i A') }}
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.dashboard.events.requests.edit', ['id' => $eventRequest->id]) }}"
                       class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Details
                    </a>
                    
                    <!-- Approve Button -->
                    <form method="POST" action="{{ route('admin.dashboard.events.requests.approve', ['id' => $eventRequest->id]) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to approve this event? It will be published immediately.')"
                                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve & Publish
                        </button>
                    </form>
                    
                    <!-- Reject Button -->
                    <form method="POST" action="{{ route('admin.dashboard.events.requests.reject', ['id' => $eventRequest->id]) }}"
                          onsubmit="return confirm('Are you sure you want to reject and permanently delete this request? This action cannot be undone.');" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Reject & Delete
                        </button>
                    </form>
                </div>

                <!-- Quick Info Bar -->
                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Response time: {{ $eventRequest->created_at->diffInHours() < 24 ? 'Within 24h' : $eventRequest->created_at->diffInDays() . ' days' }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Request ID: #{{ $eventRequest->id }}
                        </span>
                    </div>
                    @if($isUrgent)
                        <span class="flex items-center text-red-600 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Urgent Review
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations for this component */
    .translateY-full {
        transform: translateY(100%);
    }
    
    .group:hover .translateY-full {
        transform: translateY(0);
    }
    
    /* Enhanced hover effects */
    .event-card:hover {
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    /* Smooth transitions for action buttons */
    .event-card button,
    .event-card a {
        transition: all 0.2s ease;
    }
    
    /* Loading states */
    .event-card form[data-loading] button {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .event-card form[data-loading] button::after {
        content: '';
        width: 16px;
        height: 16px;
        margin-left: 8px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    // Add loading states to form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.event-card form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                this.setAttribute('data-loading', 'true');
            });
        });
    });
</script>