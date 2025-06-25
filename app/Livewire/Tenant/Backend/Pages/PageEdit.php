<?php

namespace App\Livewire\Tenant\Backend\Pages;

use App\Models\Page;
use Livewire\Component;

class PageEdit extends Component
{
    public Page $page;

    protected $rules = [
        'page.title' => 'required|string|max:255',
        'page.content_html' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();
        $this->page->save();

        session()->flash('success', 'Page updated successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.backend.pages.page-edit');
    }
}
