{{--<x-tenant-layout>--}}
    <div class="p-6">
        @if(auth('tenant')->check())
            <h1>Welcome, {{ auth('tenant')->user()->name }}</h1>
        @else
            <p>You are not authenticated.</p>
        @endif
    </div>
{{--</x-tenant-layout>--}}
