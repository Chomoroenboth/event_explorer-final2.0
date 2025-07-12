<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Explorer</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- @vite('resources/css/app.css') --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-black text-white min-h-screen">
    <header class="bg-black px-6 py-4 flex items-center justify-between sticky top-0 z-50">
        <a class="flex items-center" href="{{ url('/') }}">
            <h1 class="text-3x  l font-bold text-white tracking-tight">Event<br>Explorer</h1>
        </a>
        <div class="flex items-center space-x-4">
            <!-- Propose Event Button -->
            <a href="{{ route('events.propose') }}"
                class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-gray-200 transition-all duration-200 text-sm">
                Propose an event
            </a>

            @if (auth('web')->check())
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileButton"
                        class="w-10 h-10 rounded-full overflow-hidden border-2 border-white border-opacity-20 hover:border-opacity-40 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                        <img src="https://whatsondisneyplus.b-cdn.net/wp-content/uploads/2022/12/spiderman.png"
                            alt="Profile" class="w-full h-full object-cover">
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdown"
                        class="absolute right-0 mt-2 w-48 glassmorphism rounded-xl shadow-2xl opacity-0 invisible scale-95 transition-all duration-200 ease-out z-50 overflow-hidden">
                        <div class="py-2">
                            <a href="{{ route('events.saved') }}"
                                class="flex items-center px-4 py-3 text-white hover:bg-gray-800 hover:text-white transition-all duration-200 rounded-lg mx-2">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                                <span class="font-medium">Saved Events</span>
                            </a>
                            <div class="border-t border-white border-opacity-20 my-2 mx-4"></div>
                            <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                                {{-- CSRF token for security --}}
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-3 text-white hover:bg-red-500 hover:text-white transition-all duration-200 rounded-lg mx-2">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Login Button -->
                <a href="{{ route('login') }}"
                    class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-gray-100 transition-all duration-200 text-sm">
                    Login
                </a>
            @endif
        </div>
    </header>

    <main class="container mx-auto p-8">
        @yield('content')
    </main>

    <script>
        // Profile dropdown functionality
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileButton && profileDropdown) {
            profileButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleDropdown();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            function toggleDropdown() {
                if (profileDropdown.classList.contains('opacity-0')) {
                    openDropdown();
                } else {
                    closeDropdown();
                }
            }

            function openDropdown() {
                profileDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                profileDropdown.classList.add('opacity-100', 'visible', 'scale-100');
            }

            function closeDropdown() {
                profileDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                profileDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
            }

            // Keyboard navigation for accessibility
            profileButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleDropdown();
                }
            });
        }
    </script>
</body>

</html>
