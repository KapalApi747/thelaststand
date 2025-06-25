<?php

namespace App\Livewire\Tenant\Backend\Pages;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('t-dashboard-layout')]
class PageEdit extends Component
{
    public Page $page;

    public $title, $slug, $content_html, $is_active;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('pages', 'slug')->ignore($this->page->id),
            ],
            'content_html' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function mount(Page $page)
    {
        $this->page = $page;

        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content_html = $page->content_html;
        $this->is_active = $page->is_active;
    }

    public function updated($value)
    {
        $this->validateOnly($value);
    }

    public function save()
    {
        $this->validate();

        $this->page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content_html' => $this->content_html,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Page updated successfully!');
    }

    public function render()
    {
        return view('livewire.tenant.backend.pages.page-edit');
    }
}
