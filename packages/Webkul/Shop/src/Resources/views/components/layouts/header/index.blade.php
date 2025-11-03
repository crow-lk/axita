{!! view_render_event('bagisto.shop.layout.header.before') !!}

<header id="main-header" class="shadow-gray sticky top-0 z-50 bg-white shadow-sm max-lg:shadow-none">
    @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
        <div class="max-lg:hidden">
            <x-shop::layouts.header.desktop.top />
        </div>
    @endif

    <x-shop::layouts.header.desktop />
    <x-shop::layouts.header.mobile />
</header>

<!-- Add spacing to prevent content jump -->
<div id="header-spacer"></div>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
