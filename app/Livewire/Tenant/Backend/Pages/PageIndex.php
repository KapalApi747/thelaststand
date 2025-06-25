<?php

namespace App\Livewire\Tenant\Backend\Pages;

use App\Models\Page;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('t-dashboard-layout')]
class PageIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Page $page)
    {
        $page->delete();
        session()->flash('success', 'Page deleted successfully!');
    }

    public function render()
    {
        $pages = Page::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->orderBy('title')
            ->paginate(10);

        return view('livewire.tenant.backend.pages.page-index', compact('pages'));
    }
}
