<?php

namespace App\Livewire\Tenant\Frontend\Main;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tenant-landing')]
class Homepage extends Component
{
    public function render()
    {
        return view('livewire.tenant.frontend.main.homepage');
    }
}
