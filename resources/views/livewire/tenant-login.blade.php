<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div>
        <h2>Login to your Tenant</h2>

        @if($errorMessage)
            <div class="error">{{ $errorMessage }}</div>
        @endif

        <form wire:submit.prevent="login">
            @csrf
            <div>
                <label for="email">Email</label>
                <input type="email" wire:model="email" id="email" required />
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" wire:model="password" id="password" required />
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

</div>
