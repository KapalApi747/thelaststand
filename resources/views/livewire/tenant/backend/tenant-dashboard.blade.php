<div>
    @can('view dashboard')
        {{-- General Report --}}
        <livewire:tenant.backend.components.general-report/>

        {{--Analytics --}}
        <livewire:tenant.backend.components.analytics/>

        {{--Sales Overview--}}
        <livewire:tenant.backend.components.sales-overview/>

        {{--Numbers--}}
        <livewire:tenant.backend.components.numbers/>

        {{--Quick Info--}}
        <livewire:tenant.backend.components.quick-info/>
    @else
        <div class="alert alert-dark">
            You do not have permission to view the dashboard.
        </div>
    @endcan
</div>
