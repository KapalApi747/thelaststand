<div class="space-y-6">

    @if (session()->has('message'))
        <div class="text-green-600">
            {{ session('message') }}
        </div>
    @endif

</div>
