<div>
    {{-- The Master doesn't talk, he acts. --}}
    <h1 class="text-2xl font-bold mb-4">Tenant Login</h1>

    @if($errorMessage)
        <div class="error">{{ $errorMessage }}</div>
    @endif

    <form wire:submit="login">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="email" wire:model="email" id="email" required/>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" wire:model="password" id="password" required/>
        </div>

        <button type="submit">Login</button>
    </form>
    <flux:button type="primary" wire:click="testButton">Test Communication</flux:button>
</div>
