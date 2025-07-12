@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center min-h-[80vh]">
        <div class="glassmorphism p-8 rounded-2xl shadow-lg w-full max-w-md border border-white border-opacity-20">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

            @if ($errors->any())
                <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-500 bg-opacity-10 border border-green-500 text-green-200 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">

                <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-500 text-black focus:outline-none focus:ring-2 focus:ring-white">

                <button type="submit"
                    class="w-full bg-white text-black font-medium py-3 rounded-full hover:bg-gray-100 transition-all duration-200">
                    Register
                </button>
            </form>
        </div>
    </div>
@endsection
