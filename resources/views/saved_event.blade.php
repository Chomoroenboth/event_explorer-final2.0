@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold text-white mb-6">Saved Events</h1>

        @if (session('success'))
            <div class="bg-green-500 bg-opacity-20 border border-green-500 text-white px-4 py-3 rounded-lg mb-6"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif


        @forelse ($events as $e)
            <x-event-card :eventRequest="$e" />
        @empty
            <div class="bg-gray-800 border border-gray-700 rounded-lg text-center p-12">
                <p class="text-gray-400 text-lg">You do not have any saved events. ✨</p>
                <a href="{{ route('home') }}"
                    class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Start exploring events
                </a>
            </div>
        @endforelse

    </div>
@endsection
