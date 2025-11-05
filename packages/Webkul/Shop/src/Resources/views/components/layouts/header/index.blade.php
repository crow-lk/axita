{!! view_render_event('bagisto.shop.layout.header.before') !!}

@if(request()->routeIs('shop.home.index'))
    {{-- Home Page: Regular scrolling header --}}
    <x-shop::layouts.header.home />
@else
    {{-- Other Pages: Sticky header --}}
    <x-shop::layouts.header.shop />
@endif

{!! view_render_event('bagisto.shop.layout.header.after') !!}
