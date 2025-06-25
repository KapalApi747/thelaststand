<div class="min-h-screen px-8 py-12">

    <div class="text-center">
        <h1 class="text-3xl font-bold mb-6 text-black">{{ $page->title }}</h1>
    </div>

    <article class="prose text-black text-center">
        {!! tenantPlaceholders($page->content_html) !!}
    </article>
</div>
