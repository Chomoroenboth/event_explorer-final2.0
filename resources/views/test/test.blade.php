{{-- Check if user is logged in --}}
@if (auth('web')->check())
    <p><strong>User logged in:</strong> Yes</p>
    <p>Email: {{ auth('web')->user()->email }}</p>
    {{-- Add more user details here --}}
@else
    <p><strong>User logged in:</strong> No</p>
@endif

<hr>

{{-- Check if admin is logged in --}}
@if (auth('admin')->check())
    <p><strong>Admin logged in:</strong> Yes</p>
    <p>Email: {{ auth('admin')->user()->email }}</p>
    <p>Role: {{ auth('admin')->user()->role }}</p>
    {{-- Add more admin details here --}}
@else
    <p><strong>Admin logged in:</strong> No</p>
@endif
