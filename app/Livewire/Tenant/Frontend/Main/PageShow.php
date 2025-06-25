<?php

namespace App\Livewire\Tenant\Frontend\Main;

use App\Models\Page;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-shop-layout')]
class PageShow extends Component
{
    public Page $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.tenant.frontend.main.page-show', [
            'title' => $this->page->title,
            'content_html' => tenantPlaceholders($this->page->content_html),
        ]);
    }
}
