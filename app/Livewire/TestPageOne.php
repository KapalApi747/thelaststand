<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TestPageOne extends Component
{
    public function render()
    {
        return view('livewire.test-page-one', [
            'user' => Auth::user()
        ])->layout('components.layouts.guest.guest');
    }
}
