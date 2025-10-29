<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');

    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp

<div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
    <!-- Top Row: Logo, Icons -->
    <div class="flex w-full items-center justify-between mb-2">
        <!-- Left Navigation -->
        <div class="flex items-center gap-x-1.5">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <x-shop::drawer
                position="left"
                width="100%"
            >
                <x-slot:toggle>
                    <span class="icon-hamburger cursor-pointer text-2xl"></span>
                </x-slot>

                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('shop.home.index') }}">
                            <img
                                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                                alt="{{ config('app.name') }}"
                                width="160"
                                height="36"
                            >
                        </a>
                    </div>
                </x-slot>

                <x-slot:content>
                    <!-- Account Profile Hero Section -->
                    <div class="mb-4 grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 p-2.5 max-md:mt-4">
                        <div>
                            <img
                                src="{{ auth()->user()?->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                                class="h-[60px] w-[60px] rounded-full max-md:rounded-full"
                            >
                        </div>

                        @guest('customer')
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="flex text-base font-medium"
                            >
                                @lang('shop::app.components.layouts.header.mobile.login')

                                <i class="icon-double-arrow text-2xl ltr:ml-2.5 rtl:mr-2.5"></i>
                            </a>
                        @endguest

                        @auth('customer')
                            <div class="flex flex-col justify-between gap-2.5 max-md:gap-0">
                                <p class="font-mediums break-all text-2xl max-md:text-xl">Hello! {{ auth()->user()?->first_name }}</p>

                                <p class="text-zinc-500 no-underline max-md:text-sm">{{ auth()->user()?->email }}</p>
                            </div>
                        @endauth
                    </div>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                    <!-- Mobile category view -->
                    <v-mobile-category></v-mobile-category>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
                </x-slot>

                <x-slot:footer></x-slot>
            </x-shop::drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="max-h-[40px]"
                aria-label="@lang('shop::app.components.layouts.header.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="160"
                    height="36"
                >
            </a>
            
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right Navigation -->
        <div>
            <div class="flex items-center gap-x-5 max-md:gap-x-4">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

                @if($showCompare)
                    <a
                        href="{{ route('shop.compare.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.compare')"
                    >
                        <span class="icon-compare cursor-pointer text-2xl"></span>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

                @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    @include('shop::checkout.cart.mini-cart')
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}

                <!-- For Large screens -->
                <div class="max-md:hidden">
                    <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                        <x-slot:toggle>
                            <span class="icon-users cursor-pointer text-2xl"></span>
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
    
                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

                                <div class="mt-6 flex gap-4">
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before') !!}

                                    <a
                                        href="{{ route('shop.customer.session.create') }}"
                                        class="m-0 mx-auto block w-max cursor-pointer rounded-2xl bg-navyBlue px-7 py-4 text-center text-base font-medium text-white ltr:ml-0 rtl:mr-0"
                                    >
                                        @lang('shop::app.components.layouts.header.sign-in')
                                    </a>
    
                                    <a
                                        href="{{ route('shop.customers.register.index') }}"
                                        class="m-0 mx-auto block w-max cursor-pointer rounded-2xl border-2 border-navyBlue bg-white px-7 py-3.5 text-center text-base font-medium text-navyBlue ltr:ml-0 rtl:mr-0"
                                    >
                                        @lang('shop::app.components.layouts.header.sign-up')
                                    </a>
    
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after') !!}
                                </div>

                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
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
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before') !!}
    
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
    
                                    @if ($showWishlist)
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
                                            id="customerLogout"
                                        />
    
                                        <a
                                            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                            href="{{ route('shop.customer.session.destroy') }}"
                                            onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                        >
                                            @lang('shop::app.components.layouts.header.logout')
                                        </a>
                                    @endauth
    
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after') !!}
                                </div>
                            </x-slot>
                        @endauth
                    </x-shop::dropdown>
                </div>

                <!-- For Medium and small screen --> 
                <div class="md:hidden">
                    @guest('customer')
                        <a
                            href="{{ route('shop.customer.session.create') }}"
                            aria-label="@lang('shop::app.components.layouts.header.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endguest

                    <!-- Customers Dropdown -->
                    @auth('customer')
                        <a
                            href="{{ route('shop.customers.account.index') }}"
                            aria-label="@lang('shop::app.components.layouts.header.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <!-- Search Bar Row -->
    <v-mobile-search></v-mobile-search>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}

</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mobile-category-template"
    >
        <div>
            <div v-if="isLoading" class="space-y-3">
                <span class="shimmer block h-12 w-full rounded-2xl" role="presentation"></span>
                <span class="shimmer block h-12 w-full rounded-2xl" role="presentation"></span>
                <span class="shimmer block h-12 w-full rounded-2xl" role="presentation"></span>
            </div>

            <div v-else class="space-y-3">
                <template v-for="category in categories" :key="category.id">
                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.before') !!}

                    <div
                        class="rounded-2xl border border-zinc-200 bg-white/90 p-4 shadow-sm backdrop-blur-sm transition-all duration-200 hover:border-zinc-300"
                        :class="{ 'ring-1 ring-zinc-900/10': category.isOpen }"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <a
                                    :href="category.url"
                                    class="block truncate text-base font-semibold text-zinc-900 transition-colors duration-200 hover:text-zinc-700"
                                >
                                    @{{ category.name }}
                                </a>

                                <p
                                    v-if="hasChildren(category)"
                                    class="mt-1 text-xs text-zinc-500"
                                >
                                    Tap to explore
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    v-if="! hasChildren(category)"
                                    :href="category.url"
                                    class="inline-flex items-center gap-2 rounded-full border border-zinc-900/20 bg-zinc-900/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition-all duration-200 hover:bg-zinc-900 hover:text-white"
                                >
                                    <span>View all</span>
                                    <span class="icon-arrow-right text-sm"></span>
                                </a>

                                <button
                                    v-else
                                    type="button"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-900/10 text-lg text-zinc-500 transition-all duration-200 hover:bg-zinc-900/15 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                    @click="toggle(category)"
                                    :aria-expanded="category.isOpen"
                                    :aria-label="'Toggle ' + category.name"
                                >
                                    <span
                                        class="transition-transform duration-200"
                                        :class="{
                                            'icon-arrow-down -rotate-180 text-zinc-900': category.isOpen,
                                            'icon-arrow-right text-zinc-500': !category.isOpen
                                        }"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <transition name="mobile-collapsible">
                            <div v-if="category.isOpen && hasChildren(category)" class="mt-4 space-y-3">
                                <div class="flex justify-end">
                                    <a
                                        :href="category.url"
                                        class="inline-flex items-center gap-2 rounded-full border border-zinc-900/20 bg-zinc-900/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition-all duration-200 hover:bg-zinc-900 hover:text-white"
                                    >
                                        <span>View all</span>
                                        <span class="icon-arrow-right text-sm"></span>
                                    </a>
                                </div>

                                <div
                                    v-for="second in category.children"
                                    :key="second.id"
                                    class="rounded-xl border border-zinc-200 bg-white p-3 shadow-sm transition-all duration-200 hover:border-zinc-300"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <a
                                                :href="second.url"
                                                class="block truncate text-sm font-semibold text-zinc-800 transition-colors duration-200 hover:text-zinc-600"
                                            >
                                                @{{ second.name }}
                                            </a>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button
                                                v-if="hasChildren(second)"
                                                type="button"
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-900/5 text-base text-zinc-500 transition-all duration-200 hover:bg-zinc-900/10 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                                @click.stop="toggleSecond(category, second)"
                                                :aria-expanded="second.isOpen"
                                                :aria-label="'Toggle ' + second.name"
                                            >
                                                <span
                                                    class="transition-transform duration-200"
                                                    :class="{
                                                        'icon-arrow-down -rotate-180 text-zinc-900': second.isOpen,
                                                        'icon-arrow-right text-zinc-500': !second.isOpen
                                                    }"
                                                ></span>
                                            </button>

                                            <a
                                                v-else
                                                :href="second.url"
                                                class="inline-flex items-center gap-2 rounded-full border border-zinc-900/10 bg-white px-3 py-1 text-xs font-medium uppercase tracking-wide text-zinc-700 transition-all duration-200 hover:border-zinc-900/30 hover:text-zinc-900"
                                            >
                                                <span>View all</span>
                                                <span class="icon-arrow-right text-xs"></span>
                                            </a>
                                        </div>
                                    </div>

                                    <transition name="mobile-collapsible">
                                        <div v-if="second.isOpen && hasChildren(second)" class="mt-3 space-y-1 ltr:pl-3 rtl:pr-3">
                                            <div class="flex justify-end">
                                                <a
                                                    :href="second.url"
                                                    class="inline-flex items-center gap-2 text-xs font-medium text-zinc-700 transition-colors duration-200 hover:text-zinc-900"
                                                >
                                                    <span>View all</span>
                                                    <span class="icon-arrow-right text-xs"></span>
                                                </a>
                                            </div>

                                                <ul class="space-y-1 text-sm text-zinc-600">
                                                    <li v-for="third in second.children" :key="third.id">
                                                        <a
                                                            :href="third.url"
                                                            class="block rounded-lg px-3 py-1.5 transition-colors duration-150 hover:bg-zinc-100 hover:text-zinc-900"
                                                        >
                                                            @{{ third.name }}
                                                        </a>
                                                    </li>
                                                </ul>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </transition>
                    </div>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.after') !!}
                </template>
            </div>
        </div>

        <!-- Localization & Currency Section -->
        @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
            <div class="w-full border-t bg-white">
                <div class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-zinc-200 bg-white px-5 ltr:left-0 rtl:right-0">
                    <!-- Filter Drawer -->
                    <x-shop::drawer
                        position="bottom"
                        width="100%"
                    >
                        <!-- Drawer Toggler -->
                        <x-slot:toggle>
                            <div
                                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-lg font-medium uppercase max-md:py-3 max-sm:text-base"
                                role="button"
                            >
                                {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                            </div>
                        </x-slot>

                        <!-- Drawer Header -->
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-semibold">
                                    @lang('shop::app.components.layouts.header.mobile.currencies')
                                </p>
                            </div>
                        </x-slot>

                        <!-- Drawer Content -->
                        <x-slot:content class="!px-0">
                            <div
                                class="overflow-auto"
                                :style="{ height: getCurrentScreenHeight }"
                            >
                                <v-currency-switcher></v-currency-switcher>
                            </div>
                        </x-slot>
                    </x-shop::drawer>

                    <!-- Seperator -->
                    <span class="h-5 w-0.5 bg-zinc-200"></span>

                    <!-- Sort Drawer -->
                    <x-shop::drawer
                        position="bottom"
                        width="100%"
                    >
                        <!-- Drawer Toggler -->
                        <x-slot:toggle>
                            <div
                                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-lg font-medium uppercase max-md:py-3 max-sm:text-base"
                                role="button"
                            >
                                <img
                                    src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                            ? core()->getCurrentLocale()->logo_url
                                            : bagisto_asset('images/default-language.svg')
                                        }}"
                                    class="h-full"
                                    alt="Default locale"
                                    width="24"
                                    height="16"
                                />

                                {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                            </div>
                        </x-slot>

                        <!-- Drawer Header -->
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-semibold">
                                    @lang('shop::app.components.layouts.header.mobile.locales')
                                </p>
                            </div>
                        </x-slot>

                        <!-- Drawer Content -->
                        <x-slot:content class="!px-0">
                            <div
                                class="overflow-auto"
                                :style="{ height: getCurrentScreenHeight }"
                            >
                                <v-locale-switcher></v-locale-switcher>
                            </div>
                        </x-slot>
                    </x-shop::drawer>
                </div>
            </div>
        @endif
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return  {
                    categories: [],
                    isLoading: true,
                }
            },

            mounted() {
                this.get();
            },

            computed: {
                getCurrentScreenHeight() {
                    return window.innerHeight - (window.innerWidth < 920 ? 61 : 0) + 'px';
                },
            },

            methods: {
                get() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            const categories = response.data.data || [];

                            this.categories = categories.map(category => this.normalizeCategory(category));
                            this.isLoading = false;
                        }).catch(error => {
                            console.log(error);

                            this.isLoading = false;
                        });
                },

                toggle(selectedCategory) {
                    if (! this.hasChildren(selectedCategory)) {
                        return;
                    }

                    this.categories = this.categories.map((category) => {
                        const baseChildren = category.children.map((child) => this.cloneChild(child));

                        if (category.id !== selectedCategory.id) {
                            return {
                                ...category,
                                isOpen: false,
                                children: baseChildren.map((child) => this.cloneChild(child, { isOpen: false })),
                            };
                        }

                        const nextOpen = ! category.isOpen;

                        return {
                            ...category,
                            isOpen: nextOpen,
                            children: baseChildren.map((child) => this.cloneChild(child, { isOpen: nextOpen ? child.isOpen : false })),
                        };
                    });
                },

                toggleSecond(parentCategory, secondCategory) {
                    if (! this.hasChildren(secondCategory)) {
                        return;
                    }

                    this.categories = this.categories.map((category) => {
                        if (category.id !== parentCategory.id) {
                            return {
                                ...category,
                                children: category.children.map((child) => this.cloneChild(child, { isOpen: false })),
                            };
                        }

                        return {
                            ...category,
                            children: category.children.map((child) => {
                                if (child.id !== secondCategory.id) {
                                    return this.cloneChild(child, { isOpen: false });
                                }

                                return this.cloneChild(child, { isOpen: ! child.isOpen });
                            }),
                        };
                    });
                },

                hasChildren(node) {
                    return Array.isArray(node.children) && node.children.length > 0;
                },

                normalizeCategory(category) {
                    return {
                        ...category,
                        isOpen: false,
                        children: (category.children || []).map((child) => this.normalizeChild(child)),
                    };
                },

                normalizeChild(child) {
                    return this.cloneChild(child, { isOpen: false });
                },

                cloneChild(child, overrides = {}) {
                    const children = (child.children || []).map((grandChild) => ({ ...grandChild }));

                    return {
                        ...child,
                        ...overrides,
                        children,
                    };
                },
            },
        });

        app.component('v-mobile-search', {
            template: `
                <div class="relative w-full">
                    <form action="{{ route('shop.search.index') }}" class="flex flex-col w-full gap-2">
                        <label for="mobile-search" class="sr-only">@lang('shop::app.components.layouts.header.search')</label>
                        
                        <!-- Unified Rounded Search Bar with Category -->
                        <div class="flex flex-col w-full rounded-2xl border border-gray-300 bg-white overflow-hidden">
                            <!-- Category Dropdown -->
                            <div class="relative">
                                <select 
                                    name="category"
                                    v-model="selectedCategory"
                                    @change="search"
                                    class="w-full px-4 py-2.5 pr-10 text-xs font-medium text-gray-700 bg-gray-50 border-b border-gray-300 outline-none cursor-pointer appearance-none"
                                >
                                    <option value="">All Categories</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        @{{ category.name }}
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
                                    <span class="icon-arrow-right text-lg text-gray-600"></span>
                                </div>
                            </div>
                            
                            <!-- Search Input Row -->
                            <div class="relative flex items-center">
                                <div class="icon-search pointer-events-none flex items-center text-lg text-gray-400 ltr:ml-3 rtl:mr-3"></div>
                                
                                <input
                                    type="text"
                                    id="mobile-search"
                                    name="query"
                                    v-model="query"
                                    @input="search"
                                    @focus="showDropdown = true"
                                    class="flex-grow px-3 py-2.5 text-sm font-medium text-gray-900 bg-transparent border-none outline-none placeholder-gray-400"
                                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                                    autocomplete="off"
                                >
                                
                                <button type="submit" class="px-4 py-2 text-[#e85805] hover:text-[#d14805]">
                                    <span class="icon-arrow-right text-lg"></span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Dropdown -->
                    <div v-if="showDropdown && query.length >= 2" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-50 max-h-80 overflow-y-auto">
                        <!-- Loading -->
                        <div v-if="loading" class="p-3 text-center">
                            <div class="inline-block animate-spin h-4 w-4 border-2 border-gray-300 border-t-blue-600 rounded-full"></div>
                            <span class="ml-2 text-xs text-gray-600">Searching...</span>
                        </div>

                        <!-- Results -->
                        <div v-else-if="products.length > 0">
                            <a
                                v-for="product in products"
                                :key="product.id"
                                :href="'{{ url('') }}/' + product.url"
                                class="flex items-center gap-2 p-2.5 hover:bg-gray-50 border-b last:border-b-0"
                            >
                                <img :src="product.image" :alt="product.name" class="w-10 h-10 object-cover rounded bg-gray-100">
                                <div class="flex-grow min-w-0">
                                    <p class="text-xs font-medium text-gray-900 truncate">@{{ product.name }}</p>
                                    <p class="text-xs text-[#e85805] font-semibold">@{{ product.formatted_price }}</p>
                                </div>
                            </a>
                        </div>

                        <!-- No Results -->
                        <div v-else class="p-3 text-center text-xs text-gray-600">
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

@pushOnce('styles')
    <style>
        .mobile-collapsible-enter-active,
        .mobile-collapsible-leave-active {
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .mobile-collapsible-enter-from,
        .mobile-collapsible-leave-to {
            opacity: 0;
            transform: translateY(-6px);
            max-height: 0;
        }

        .mobile-collapsible-enter-to,
        .mobile-collapsible-leave-from {
            opacity: 1;
            transform: translateY(0);
            max-height: 600px;
        }
    </style>
@endPushOnce
