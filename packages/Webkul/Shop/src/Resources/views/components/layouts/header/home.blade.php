{{-- Home Page Header (Scrolls with page) --}}
<header class="bg-white shadow-sm max-lg:shadow-none">
    @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
        <div class="max-lg:hidden">
            <x-shop::layouts.header.desktop.top />
        </div>
    @endif

    <x-shop::layouts.header.desktop />
    <x-shop::layouts.header.mobile />
</header>
