@extends('layouts.app_admin')

@section('page-title', 'Event Requests')
@section('page-description', 'Review and manage pending event submissions')

@section('content')
<div class="fade-in">
    <!-- Enhanced Stats Cards -->
    <!-- Dashboard Stats Cards HTML Structure -->
    <div class="stats-grid">
        <!-- Pending Requests Card -->
        <div class="stats-card stats-card-blue fade-in">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number" data-pending-count>0</div>
                    <div class="stats-label">Pending Requests</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 65%"></div>
                </div>
            </div>
            <div class="stats-footer">
                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2 pulse"></div>
                <span>Requires attention</span>
            </div>
        </div>

        <!-- Approved Events Card -->
        <div class="stats-card stats-card-green fade-in" style="animation-delay: 0.1s">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">0</div>
                    <div class="stats-label">Approved Events</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 85%"></div>
                </div>
            </div>
            <div class="stats-footer stats-trend-up">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>+12% this month</span>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="stats-card stats-card-orange fade-in" style="animation-delay: 0.2s">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">1</div>
                    <div class="stats-label">Total Users</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 92%"></div>
                </div>
            </div>
            <div class="stats-footer">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <span>Active community</span>
            </div>
        </div>

        <!-- Approval Rate Card -->
        <div class="stats-card stats-card-purple fade-in" style="animation-delay: 0.3s">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">0%</div>
                    <div class="stats-label">Approval Rate</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 0%"></div>
                </div>
            </div>
            <div class="stats-footer">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>High efficiency</span>
            </div>
        </div>
    </div>

    <!-- Second Row of Cards -->
    <div class="stats-grid">
        <!-- Published Events Card -->
        <div class="stats-card stats-card-green fade-in">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">0</div>
                    <div class="stats-label">Published Events</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <!-- Free Events Card -->
        <div class="stats-card stats-card-blue fade-in" style="animation-delay: 0.1s">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">0</div>
                    <div class="stats-label">Free Events</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <!-- Paid Events Card -->
        <div class="stats-card stats-card-purple fade-in" style="animation-delay: 0.2s">
            <div class="stats-card-header">
                <div class="stats-content">
                    <div class="stats-number">0</div>
                    <div class="stats-label">Paid Events</div>
                </div>
                <div class="stats-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="stats-progress">
                <div class="stats-progress-bar">
                    <div class="stats-progress-fill" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Alerts -->
    @if (session('success'))
    <div class="alert alert-success slide-in-right" role="alert">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <div>
            <p class="font-semibold">Success!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error slide-in-right" role="alert">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <div>
            <p class="font-semibold">Error!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Quick Actions Bar -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Actions</h3>
                <p class="text-sm text-gray-600">Manage your events efficiently</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.events.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Event
                </a>
                <a href="{{ route('admin.events') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    View All Events
                </a>
                @if($eventRequests->count() > 0)
                <div class="flex items-center px-4 py-2 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2 pulse"></div>
                    <span class="text-sm font-medium text-yellow-800">{{ $eventRequests->count() }} pending</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Event Requests Container -->
    <div class="events-container">
        <div class="events-header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        Pending Event Requests
                    </h2>
                    <p class="text-gray-600 mt-2">Review and approve submitted events from the community</p>
                </div>
                <div class="flex items-center space-x-4">
                    @if($eventRequests->count() > 0)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $eventRequests->count() }} awaiting review
                    </span>
                    @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        All caught up!
                    </span>
                    @endif
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Last updated: {{ now()->format('M j, g:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <div id="events-container">
            @forelse ($eventRequests as $index => $eq)
            <div class="event-item fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                @if(View::exists('components.admin.event-request-card'))
                <x-admin.event-request-card :eventRequest="$eq" />
                @else
                {{-- Fallback in case the component doesn't exist --}}
                <div class="event-card bg-white rounded-xl shadow-lg p-6 mb-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $eq->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($eq->description ?? '', 100) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ $eq->created_at->format('M j, Y') }}</span>
                        <div class="flex space-x-2">
                            <button class="btn-primary btn-sm">Approve</button>
                            <button class="btn-secondary btn-sm">Reject</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">All Requests Processed!</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8">Great job! There are no pending event requests at the moment. All submissions have been reviewed.</p>
                <div class="flex items-center justify-center space-x-4">
                    <a href="{{ route('admin.events.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create New Event
                    </a>
                    <a href="{{ route('admin.events') }}" class="btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        View Published Events
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Activity Feed -->
    @if($eventRequests->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <span class="text-sm text-gray-500">Last 24 hours</span>
        </div>
        <div class="space-y-4">
            @foreach($eventRequests->take(3) as $request)
            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">
                        New event request: <span class="text-blue-600">{{ $request->title }}</span>
                    </p>
                    <p class="text-sm text-gray-500">
                        Submitted {{ $request->created_at->diffForHumans() }}
                        @if(isset($request->requester_email))
                        by {{ $request->requester_email }}
                        @endif
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <span class="status-badge status-{{ $request->approval_status }}">
                        {{ ucfirst($request->approval_status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Add smooth scrolling and enhanced interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats on load
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });

        // Add hover effects for event cards
        const eventCards = document.querySelectorAll('.event-card');
        eventCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
                this.style.transition = 'transform 0.3s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Auto-refresh pending count every 30 seconds
        setInterval(function() {
            fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newCount = doc.querySelector('[data-pending-count]');
                    const currentCount = document.querySelector('[data-pending-count]');

                    if (newCount && currentCount && newCount.textContent !== currentCount.textContent) {
                        // Show notification of change
                        showNotification('New event request received!', 'info');
                        // Optionally reload the page or update the content
                        // window.location.reload();
                    }
                })
                .catch(error => console.log('Refresh check failed:', error));
        }, 30000);
    });

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        if (type === 'info') {
            notification.classList.add('bg-blue-500', 'text-white');
        } else if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
        }

        notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${message}</span>
                    <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
</script>
@endpush
@endsection