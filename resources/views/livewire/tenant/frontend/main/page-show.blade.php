<div class="space-y-6 p-6">

    <div class="text-center">
        <h1 class="text-3xl font-bold mb-6 text-black">{{ $page->title }}</h1>
    </div>

    <article class="prose text-black text-center">
        {!! tenantPlaceholders($page->content_html) !!}
    </article>
</div>
