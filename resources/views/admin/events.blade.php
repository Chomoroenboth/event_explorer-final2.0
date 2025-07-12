@extends('layouts.app_admin')

@section('page-title', 'Published Events')
@section('page-description', 'View and manage all approved events')

@section('content')
<div class="fade-in space-y-8">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stats-card stats-card-green">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">{{ $events->count() }}</h3>
                    <p class="text-green-100 text-sm font-medium">Published Events</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all duration-1000" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <div class="stats-card stats-card-orange">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">{{ \App\Models\User::count() }}</h3>
                    <p class="text-orange-100 text-sm font-medium">Total Users</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all duration-1000" style="width: 92%"></div>
                </div>
            </div>
        </div>

        <div class="stats-card stats-card-blue">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">{{ $events->where('is_free', true)->count() }}</h3>
                    <p class="text-blue-100 text-sm font-medium">Free Events</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all duration-1000" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <div class="stats-card stats-card-purple">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">{{ $events->where('is_free', false)->count() }}</h3>
                    <p class="text-purple-100 text-sm font-medium">Paid Events</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all duration-1000" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
    <div class="alert alert-success">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Filter Section with Create Event Button -->
    <div class="filter-section">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Filter Events</h3>
            </div>
            
            <!-- CREATE EVENT BUTTON -->
            <a href="{{ route('admin.events.create') }}" class="btn-create-event">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Create Event</span>
            </a>
        </div>

        <div class="filter-controls">
            <div class="filter-group">
                <label for="category-filter" class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Category:
                </label>
                <select id="category-filter" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach (\App\Models\EventRequest::getCategories() as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="area-filter" class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Area:
                </label>
                <select id="area-filter" class="filter-select">
                    <option value="">All Areas</option>
                    @foreach (\App\Models\EventRequest::getAreas() as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="fee-filter" class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Fee:
                </label>
                <select id="fee-filter" class="filter-select">
                    <option value="">All Events</option>
                    <option value="free">Free Only</option>
                    <option value="paid">Paid Only</option>
                </select>
            </div>

            <div class="flex items-center space-x-3">
                <button id="clear-filters" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Clear
                </button>

                <div class="flex items-center space-x-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span id="results-count" class="text-sm font-medium text-blue-700">{{ $events->count() }} events</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Container -->
    <div class="events-container">
        <div class="events-header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        Published Events
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">All approved and published events</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        {{ $events->count() }} published
                    </span>
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <div id="events-container">
            @forelse ($events as $event)
            <div class="event-item"
                data-category="{{ $event->category }}"
                data-area="{{ $event->area }}"
                data-fee="{{ $event->is_free ? 'free' : 'paid' }}">
                <x-admin.published-event-card :event="$event" />
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">No events published yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">No events have been published yet. You can create new events or approve pending requests.</p>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Event
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilter = document.getElementById('category-filter');
        const areaFilter = document.getElementById('area-filter');
        const feeFilter = document.getElementById('fee-filter');
        const clearFiltersBtn = document.getElementById('clear-filters');
        const resultsCount = document.getElementById('results-count');
        const eventsContainer = document.getElementById('events-container');

        function filterEvents() {
            const categoryValue = categoryFilter.value.toLowerCase();
            const areaValue = areaFilter.value.toLowerCase();
            const feeValue = feeFilter.value.toLowerCase();

            const eventItems = eventsContainer.querySelectorAll('.event-item');
            let visibleCount = 0;

            eventItems.forEach(item => {
                const category = item.dataset.category?.toLowerCase() || '';
                const area = item.dataset.area?.toLowerCase() || '';
                const fee = item.dataset.fee?.toLowerCase() || '';

                const categoryMatch = !categoryValue || category.includes(categoryValue);
                const areaMatch = !areaValue || area.includes(areaValue);
                const feeMatch = !feeValue || fee === feeValue;

                if (categoryMatch && areaMatch && feeMatch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            resultsCount.textContent = `${visibleCount} event${visibleCount !== 1 ? 's' : ''}`;
        }

        function clearAllFilters() {
            categoryFilter.value = '';
            areaFilter.value = '';
            feeFilter.value = '';
            filterEvents();
        }

        categoryFilter.addEventListener('change', filterEvents);
        areaFilter.addEventListener('change', filterEvents);
        feeFilter.addEventListener('change', filterEvents);
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    });
</script>
@endsection