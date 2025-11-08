{{-- Home Page Header (Scrolls with page) --}}
<header class="bg-white shadow-sm max-lg:shadow-none">
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
        <x-shop::layouts.header.desktop.bottom />
    </div>
    
    <div class="lg:hidden">
        <x-shop::layouts.header.mobile />
    </div>
</header>
