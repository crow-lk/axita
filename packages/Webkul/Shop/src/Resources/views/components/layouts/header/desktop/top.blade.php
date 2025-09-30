{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}



<!-- Main Header -->
<div class="main-header flex items-center justify-between px-8 py-4 bg-white  border-gray-300 w-full">
    <!-- Left Section: Logo and Brand Info -->
    <div class="logo-section flex items-center gap-4">

        


        <div class="text-left">
            <a
            href="{{ route('shop.home.index') }}"
            aria-label="@lang('shop::app.components.layouts.header.bagisto')">
            <img
                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                width="131"
                height="29"
                alt="{{ config('app.name') }}"
            >
        </a>
            <h1 class="text-2xl font-bold transition-colors duration-300">Computers Online Store</h1>
            <p class="text-sm text-[#e85805]">Computers | Laptops | Accessories</p>
        </div>
    </div>

    <!-- Center Section: Attractive Sentence -->
    <div class="center-section text-center flex-grow">
        <p class="text-lg font-semibold tracking-wide">Build your dream PC from AXITA!</p>
        <p class="text-sm text-[#e85805] mt-1">Don't waste time. Just add to cart.</p>
    </div>

    <!-- Right Section: Contact Info & Social Media -->
<div class="contact-info flex flex-col items-center text-center gap-2">
    <div class="social-icons flex items-center justify-center gap-4">
        <a href="https://www.facebook.com/axitacomputers" class="text-blue-600 hover:text-blue-800 text-xl transition-all duration-300">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.tiktok.com/@axita.galle" class="text-black hover:text-red-800 text-xl transition-all duration-300" aria-label="">
            <i class="fab fa-tiktok"></i>
        </a>
        <a href="https://www.instagram.com/axita_computer/" class="text-pink-500 hover:text-pink-700 text-xl transition-all duration-300">
            <i class="fab fa-instagram"></i>
        </a>
    </div>

    <p class="text-lg font-semibold text-[#e85805] mt-2">
        <a href="tel:+94771284323" class="hover:text-[#e85805]">+94 77 128 4323</a>
    </p>
</div>

</div>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}