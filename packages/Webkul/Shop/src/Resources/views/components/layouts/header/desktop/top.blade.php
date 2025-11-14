{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<!-- Top Bar -->
<div class="full-bleed bg-gray-100 border-b border-gray-200">
    <div class="mx-auto grid w-full max-w-[1400px] grid-cols-1 sm:grid-cols-3 items-center px-1 py-2 text-xs sm:px-6 sm:py-1 sm:text-sm lg:px-8">
        <!-- Left Column (empty to center the middle content) -->
        <div></div>

        <!-- Middle Column: Status Link -->
        <div class="text-center">
            <a href="http://axita.winsoft.site/repair-status" class="font-normal text-sm text-gray-600 transition-colors hover:text-[#e85805] sm:text-base">
                Check your service status from here.
            </a>
        </div>

        <!-- Right Column: Social Icons + Phone (single line) -->
        <div class="flex items-center justify-end gap-3">
            <div class="hidden sm:flex items-center gap-3">
                <a href="https://www.facebook.com/axitacomputers" class="text-gray-500 transition-all duration-300 hover:text-gray-800" aria-label="Facebook">
                    <i class="fab fa-facebook-f text-sm sm:text-base"></i>
                </a>
                <a href="https://www.tiktok.com/@axita.galle" class="text-gray-500 transition-all duration-300 hover:text-gray-800" aria-label="TikTok">
                    <i class="fab fa-tiktok text-sm sm:text-base"></i>
                </a>
                <a href="https://www.instagram.com/axita_computer/" class="text-gray-500 transition-all duration-300 hover:text-gray-800" aria-label="Instagram">
                    <i class="fab fa-instagram text-sm sm:text-base"></i>
                </a>
            </div>
            <a href="tel:+94771284323" class="text-sm font-semibold text-[#e85805] transition-colors hover:text-[#d14805] sm:text-base">+94 77 128 4323</a>
        </div>
    </div>
</div>

<!-- Main Header -->
<div class="main-header bg-white w-full">
    <!-- Top Row: Logo, Search Bar, Contact -->
    <div class="mx-auto flex w-full flex-col md:flex-row items-center justify-center gap-3 md:gap-6 px-1 py-2 sm:px-6 lg:px-8">
        <!-- Left Section: Logo and Brand Info -->
        <div class="logo-section flex items-center gap-4 min-w-fit">
            <div class="text-left">
                <a
                    href="{{ route('shop.home.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.bagisto')"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        width="140"
                        height="32"
                        alt="{{ config('app.name') }}"
                    >
                </a>
                <h1 class="text-base font-bold transition-colors duration-300">Computers Online Store</h1>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.search_bar.before') !!}

        <!-- Center Section: Search Bar -->
        <div class="w-full md:flex-grow md:max-w-2xl md:mx-6 mt-3 md:mt-0">
            <v-search-autocomplete></v-search-autocomplete>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.search_bar.after') !!}

    <!-- Right Section: Compare, Wishlist, Cart, User -->
    <div class="hidden md:flex items-center gap-x-8 min-w-fit">
            <!-- Compare -->
            @if(core()->getConfigData('catalog.products.settings.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.compare')"
                >
                    <span
                        class="icon-compare inline-block cursor-pointer text-2xl"
                        role="presentation"
                    ></span>
                </a>
            @endif

            <!-- Wishlist -->
            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                <a
                    href="{{ route('shop.customers.account.wishlist.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.wishlist')"
                >
                    <span class="icon-heart inline-block cursor-pointer text-2xl"></span>
                </a>
            @endif

            <!-- Mini cart -->
            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            <!-- User Profile Dropdown -->
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span
                        class="icon-users inline-block cursor-pointer text-2xl"
                        role="button"
                        aria-label="@lang('shop::app.components.layouts.header.profile')"
                        tabindex="0"
                    ></span>
                </x-slot>

                <!-- Guest Dropdown -->
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-2.5">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="mt-3 w-full border border-zinc-200"></p>

                        <div class="mt-6 flex gap-4">
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 text-center text-base max-md:rounded-lg ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 text-center text-base max-md:rounded-lg max-md:py-3 ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>
                        </div>
                    </x-slot>
                @endguest

                <!-- Customers Dropdown -->
                @auth('customer')
                    <x-slot:content class="!p-0">
                        <div class="grid gap-2.5 p-5 pb-0">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="mt-3 w-full border border-zinc-200"></p>

                        <div class="mt-2.5 grid gap-1 pb-2.5">
                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.wishlist')
                                </a>
                            @endif

                            <!--Customers logout-->
                            @auth('customer')
                                <x-shop::form
                                    method="DELETE"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogoutTop"
                                />

                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogoutTop').submit();"
                                >
                                    @lang('shop::app.components.layouts.header.logout')
                                </a>
                            @endauth
                        </div>
                    </x-slot>
                @endauth
            </x-shop::dropdown>
        </div>
        
        <!-- Mobile Controls: show cart and menu icon -->
        <div class="flex md:hidden items-center gap-4 mt-2 w-full justify-between">
            <div class="flex items-center gap-3">
                @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    @include('shop::checkout.cart.mini-cart')
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="p-2 rounded-md bg-white border border-zinc-200 shadow-sm" aria-label="Menu">
                    <i class="fas fa-bars text-lg"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@pushOnce('scripts')
    <script type="module">
        app.component('v-search-autocomplete', {
            template: `
                <div class="relative">
                    <form action="{{ route('shop.search.index') }}" class="relative w-full" role="search">
                        <label for="organic-search" class="sr-only">@lang('shop::app.components.layouts.header.search')</label>
                        
                        <!-- Unified Search Bar with Category Dropdown -->
                        <div class="flex items-center w-full rounded-full border border-gray-300 bg-white overflow-hidden transition-all hover:border-gray-400 focus-within:border-gray-400 focus-within:ring-2 focus-within:ring-gray-200">
                            <!-- Category Dropdown -->
                            <select 
                                name="category"
                                v-model="selectedCategory"
                                @change="search"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-transparent border-none outline-none cursor-pointer hover:text-gray-900 min-w-[130px] max-w-[170px] appearance-none"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%228%22 viewBox=%220 0 12 8%22%3E%3Cpath fill=%22%23666%22 d=%22M1.41 0L6 4.59 10.59 0 12 1.41l-6 6-6-6z%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.5rem center; padding-right: 2rem;"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    @{{ category.name }}
                                </option>
                            </select>
                            
                            <!-- Divider -->
                            <div class="h-6 w-px bg-gray-300"></div>
                            
                            <!-- Search Icon -->
                            <div class="icon-search pointer-events-none flex items-center text-lg text-gray-400 ltr:ml-2 rtl:mr-2"></div>
                            
                            <!-- Search Input -->
                            <input
                                type="text"
                                name="query"
                                id="organic-search"
                                v-model="query"
                                @input="search"
                                @focus="showDropdown = true"
                                class="flex-grow px-2 py-2 text-sm font-medium text-gray-900 bg-transparent border-none outline-none placeholder-gray-400"
                                placeholder="@lang('shop::app.components.layouts.header.search-text')"
                                autocomplete="off"
                            >
                            
                            <!-- Search Submit Button -->
                            <button type="submit" class="px-3 text-gray-600 hover:text-gray-900 transition-colors">
                                <span class="icon-arrow-right text-lg"></span>
                            </button>
                        </div>
                    </form>

                    <!-- Dropdown -->
                    <div v-if="showDropdown && query.length >= 2" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
                        <!-- Loading -->
                        <div v-if="loading" class="p-4 text-center">
                            <div class="inline-block animate-spin h-5 w-5 border-2 border-gray-300 border-t-blue-600 rounded-full"></div>
                            <span class="ml-2 text-sm text-gray-600">Searching...</span>
                        </div>

                        <!-- Results -->
                        <div v-else-if="products.length > 0">
                            <a
                                v-for="product in products"
                                :key="product.id"
                                :href="'{{ url('') }}/' + product.url"
                                class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b last:border-b-0"
                            >
                                <img :src="product.image" :alt="product.name" class="w-12 h-12 object-cover rounded bg-gray-100">
                                <div class="flex-grow">
                                    <p class="text-sm font-medium text-gray-900 truncate">@{{ product.name }}</p>
                                    <p class="text-sm text-[#e85805] font-semibold">@{{ product.formatted_price }}</p>
                                </div>
                                <span class="icon-arrow-right text-gray-400"></span>
                            </a>
                        </div>

                        <!-- No Results -->
                        <div v-else class="p-4 text-center text-sm text-gray-600">
                            No products found
                        </div>
                    </div>
                </div>
            `,

            data() {
                return {
                    query: '{{ request('query') }}',
                    selectedCategory: '{{ request('category') }}',
                    categories: [],
                    products: [],
                    loading: false,
                    showDropdown: false,
                    timeout: null
                };
            },

            mounted() {
                this.fetchCategories();
                document.addEventListener('click', this.closeDropdown);
            },

            beforeDestroy() {
                document.removeEventListener('click', this.closeDropdown);
            },

            methods: {
                fetchCategories() {
                    this.$axios.get('{{ route('shop.api.categories.index') }}')
                        .then(response => {
                            // Filter out root categories (parent_id is null)
                            this.categories = response.data.data.filter(cat => cat.parent_id !== null);
                        })
                        .catch(error => {
                            console.error('Failed to load categories:', error);
                        });
                },

                search() {
                    clearTimeout(this.timeout);
                    
                    if (this.query.length < 2) {
                        this.products = [];
                        this.showDropdown = false;
                        return;
                    }

                    this.loading = true;
                    this.showDropdown = true;

                    this.timeout = setTimeout(() => {
                        let params = { query: this.query };
                        if (this.selectedCategory) {
                            params.category = this.selectedCategory;
                        }

                        this.$axios.get('{{ route('shop.search.suggestions') }}', { params })
                        .then(response => {
                            this.products = response.data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error(error);
                            this.products = [];
                            this.loading = false;
                        });
                    }, 300);
                },

                closeDropdown(e) {
                    if (!this.$el.contains(e.target)) {
                        this.showDropdown = false;
                    }
                }
            }
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}
