{{-- Home Page Header (Scrolls with page) --}}
<header id="home-header" class="bg-white shadow-sm max-lg:shadow-none">
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
        <x-shop::layouts.header.desktop.bottom />
    </div>
    
    <div class="lg:hidden">
        <x-shop::layouts.header.mobile />
    </div>
</header>

{{-- Sticky Shop Header (Hidden by default, shows on scroll) --}}
<header id="shop-header-sticky" style="display: none;" class="sticky top-0 z-50 bg-white shadow-md max-lg:hidden">
    <div class="mx-auto flex h-[88px] w-full max-w-[1300px] items-center justify-between px-4 sm:px-6">
        {{-- Left: Logo & Categories --}}
        <div class="flex items-center gap-x-5">
            {{-- Logo with Subtitle --}}
            <a href="{{ route('shop.home.index') }}" class="flex items-center gap-3">
                <div class="text-left">
                    <img 
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}" 
                        alt="{{ config('app.name') }}"
                        width="160"
                        height="35"
                    />
                    <h1 class="text-base font-bold text-zinc-800 transition-colors duration-300">Computers Online Store</h1>
                </div>
            </a>

            {{-- Browse Categories Dropdown (Desktop) --}}
            <x-shop::dropdown
                position="bottom-left"
                trigger="hover"
                open-delay="120"
                close-delay="180"
            >
                <x-slot:toggle>
                    <button class="group hidden items-center gap-2.5 rounded-full bg-gradient-to-r from-black via-zinc-800 to-zinc-600 px-5 py-2.5 text-xs font-semibold uppercase tracking-wide text-white shadow-lg transition-all duration-200 hover:shadow-xl lg:flex">
                        <span class="icon-hamburger text-lg transition-transform duration-200 group-hover:scale-110"></span>
                        <span>Categories</span>
                        <span class="icon-arrow-down text-sm transition-transform duration-200 group-hover:-rotate-180"></span>
                    </button>
                </x-slot:toggle>

                <x-slot:content class="!p-0">
                    <div class="w-[920px] max-w-[92vw] overflow-hidden rounded-3xl border border-white/50 bg-white shadow-[0_28px_60px_rgba(15,23,42,0.12)] backdrop-blur-md" @click.stop>
                        <div class="border-b border-zinc-200 px-6 py-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.12em] text-zinc-500">All Departments</p>
                            <p class="text-lg font-semibold text-zinc-800">Browse categories</p>
                        </div>
                        <v-collapsible-categories />
                    </div>
                </x-slot:content>
            </x-shop::dropdown>
        </div>

        {{-- Center: Navigation Links (Desktop) - Static Menu --}}
        <nav class="hidden flex-1 items-center justify-center gap-x-8 text-sm font-medium text-zinc-700 lg:flex">
            <a
                href="/services"
                class="transition-colors duration-200 hover:text-[#e85805]"
            >
                Services
            </a>
            <a
                href="{{ route('shop.search.index', ['query' => 'deals']) }}"
                class="transition-colors duration-200 hover:text-[#e85805]"
            >
                Best Deals
            </a>
            <a
                href="{{ route('shop.home.payment_methods') }}"
                class="transition-colors duration-200 hover:text-[#e85805]"
            >
                Payment Methods
            </a>
            <a
                href="{{ route('shop.home.about_us') }}"
                class="transition-colors duration-200 hover:text-[#e85805]"
            >
                About Us
            </a>
            <a
                href="{{ route('shop.home.contact_us') }}"
                class="transition-colors duration-200 hover:text-[#e85805]"
            >
                Contact Us
            </a>
        </nav>

        {{-- Right Side: Icons --}}
        <div class="flex items-center gap-5 max-sm:gap-3">

                {{-- Compare --}}
                @if (core()->getConfigData('catalog.products.settings.compare_option'))
                    <a href="{{ route('shop.compare.index') }}" class="text-zinc-700 transition-colors hover:text-[#e85805]" aria-label="Compare">
                        <span class="icon-compare text-2xl"></span>
                    </a>
                @endif

                {{-- Wishlist --}}
                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <a href="{{ route('shop.customers.account.wishlist.index') }}" class="text-zinc-700 transition-colors hover:text-[#e85805]" aria-label="Wishlist">
                        <span class="icon-heart text-2xl"></span>
                    </a>
                @endif

                {{-- Cart --}}
                <a href="{{ route('shop.checkout.cart.index') }}" class="relative text-zinc-700 transition-colors hover:text-[#e85805]" aria-label="Cart">
                    <span class="icon-cart text-2xl"></span>
                    @if(cart()->getCart()?->items_count)
                        <span class="absolute -right-2 -top-2 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-[#e85805] px-1 text-xs font-semibold text-white">
                            {{ cart()->getCart()->items_count }}
                        </span>
                    @endif
                </a>

                {{-- Account Dropdown --}}
                <x-shop::dropdown position="bottom-right">
                    <x-slot:toggle>
                        <span class="icon-users inline-block cursor-pointer text-2xl text-zinc-700 transition-colors hover:text-[#e85805]" role="button" aria-label="Account" tabindex="0"></span>
                    </x-slot:toggle>

                    <x-slot:content>
                        @guest('customer')
                            <div class="grid gap-2.5 p-4">
                                <p class="font-semibold text-lg text-zinc-900">
                                    Welcome Guest!
                                </p>
                                <p class="text-sm text-zinc-600">
                                    Sign in to access your account
                                </p>
                            </div>

                            <div class="border-t border-zinc-200"></div>

                            <div class="flex gap-3 p-4">
                                <a href="{{ route('shop.customer.session.create') }}" class="flex-1 rounded-lg bg-[#e85805] px-4 py-2.5 text-center text-sm font-semibold text-white transition-colors hover:bg-[#d04d04]">
                                    Sign In
                                </a>
                                <a href="{{ route('shop.customers.register.index') }}" class="flex-1 rounded-lg border-2 border-zinc-300 px-4 py-2.5 text-center text-sm font-semibold text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50">
                                    Sign Up
                                </a>
                            </div>
                        @else
                            <div class="grid gap-1 p-4">
                                <p class="font-semibold text-base text-zinc-900">
                                    {{ auth()->guard('customer')->user()->name }}
                                </p>
                                <p class="text-sm text-zinc-500">
                                    {{ auth()->guard('customer')->user()->email }}
                                </p>
                            </div>

                            <div class="border-t border-zinc-200"></div>

                            <div class="grid gap-1 p-2">
                                <a href="{{ route('shop.customers.account.profile.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 hover:text-[#e85805]">
                                    <span class="icon-user text-lg"></span>
                                    My Profile
                                </a>
                                <a href="{{ route('shop.customers.account.orders.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 hover:text-[#e85805]">
                                    <span class="icon-shopping-bag text-lg"></span>
                                    My Orders
                                </a>
                                <a href="{{ route('shop.customers.account.wishlist.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 hover:text-[#e85805]">
                                    <span class="icon-heart text-lg"></span>
                                    Wishlist
                                </a>
                            </div>

                            <div class="border-t border-zinc-200"></div>

                            <div class="p-2">
                                <a href="{{ route('shop.customer.session.destroy') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50" onclick="event.preventDefault(); document.getElementById('logout-form-sticky').submit();">
                                    <span class="icon-logout text-lg"></span>
                                    Logout
                                </a>
                                <form id="logout-form-sticky" action="{{ route('shop.customer.session.destroy') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        @endguest
                    </x-slot:content>
                </x-shop::dropdown>

                {{-- Mobile Menu Toggle --}}
                <button class="block text-zinc-700 lg:hidden" onclick="document.querySelector('[data-drawer-mobile]')?.click()">
                    <span class="icon-hamburger text-2xl"></span>
                </button>
        </div>
    </div>
</header>


