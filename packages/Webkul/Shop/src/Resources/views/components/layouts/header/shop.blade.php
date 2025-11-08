{{-- Shop/Other Pages Header - Simple & Sticky (Desktop Only) --}}
<header class="sticky top-0 z-50 bg-white shadow-md max-lg:hidden">
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

        {{-- Center: Navigation Links (Desktop) - Dynamic Categories --}}
        <nav class="hidden flex-1 items-center justify-center gap-x-8 text-sm font-medium text-zinc-700 lg:flex">
            @php
                $categories = app('Webkul\Category\Repositories\CategoryRepository')
                    ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
                $maxItems = 6; // Limit to 6 categories to fit nicely
            @endphp

            @foreach($categories->take($maxItems) as $category)
                <a
                    href="{{ $category->url }}"
                    class="transition-colors duration-200 hover:text-[#e85805]"
                >
                    {{ $category->name }}
                </a>
            @endforeach
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
                                <a href="{{ route('shop.customer.session.destroy') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="icon-logout text-lg"></span>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('shop.customer.session.destroy') }}" method="POST" class="hidden">
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

{{-- Mobile Header for Shop Pages --}}
<div class="lg:hidden">
    <x-shop::layouts.header.mobile />
</div>

{{-- Include the category dropdown component --}}
@pushOnce('scripts')
    <script type="text/x-template" id="v-collapsible-categories-template">
        <div class="flex max-h-[70vh] divide-x divide-zinc-200">
            <div class="category-scroll w-[260px] shrink-0 overflow-y-auto bg-zinc-50/80 px-4 py-4" :style="{ scrollbarWidth: 'thin' }">
                <div v-if="isLoading" class="grid gap-2">
                    <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                    <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                    <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
                </div>

                <template v-else-if="categories.length">
                    <button
                        v-for="category in categories"
                        :key="category.id"
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg border border-transparent px-4 py-2 text-left text-sm font-semibold text-zinc-600 transition-all duration-150 hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                        :class="{ '!border-zinc-300 !bg-white text-zinc-900 shadow-sm': category.id === activeCategoryId }"
                        @mouseenter="setActiveCategory(category)"
                        @focus="setActiveCategory(category)"
                    >
                        <span class="truncate">@{{ category.name }}</span>
                        <span class="icon-arrow-right text-xs text-zinc-400"></span>
                    </button>
                </template>

                <p v-else class="px-2 py-4 text-sm text-zinc-500">
                    @lang('shop::app.components.layouts.header.no-category-found')
                </p>
            </div>

            <div class="flex-1 overflow-y-auto bg-white px-8 py-6">
                <div v-if="isLoading" class="grid gap-2">
                    <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                    <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
                    <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                </div>

                <template v-else-if="activeCategory">
                    <div v-if="activeCategory.children && activeCategory.children.length" class="flex gap-6">
                        <div class="category-scroll w-[240px] shrink-0 overflow-y-auto border-r border-zinc-100 pr-4" :style="{ scrollbarWidth: 'thin' }">
                            <a
                                v-for="child in activeCategory.children"
                                :key="child.id"
                                :href="child.url"
                                class="group flex w-full items-center justify-between rounded-lg border border-transparent px-4 py-2 text-left text-sm font-semibold text-zinc-600 transition-all duration-150 hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                :class="{ '!border-zinc-300 !bg-zinc-50 text-zinc-900 shadow-sm': child.id === activeSubcategoryId }"
                                @mouseenter="setActiveSubcategory(child)"
                                @focus="setActiveSubcategory(child)"
                            >
                                <span class="truncate">@{{ child.name }}</span>
                                <span class="icon-arrow-right text-xs text-zinc-400 transition-transform duration-150 group-hover:translate-x-1"></span>
                            </a>
                        </div>

                        <div class="flex-1 overflow-y-auto">
                            <template v-if="activeSubcategory && activeSubcategory.children && activeSubcategory.children.length">
                                <div class="grid gap-2">
                                    <a
                                        v-for="grand in activeSubcategory.children"
                                        :key="grand.id"
                                        :href="grand.url"
                                        class="flex items-center justify-between rounded-lg border border-transparent px-4 py-2 text-sm font-medium text-zinc-600 transition-colors hover:border-zinc-200 hover:bg-zinc-50 hover:text-zinc-900"
                                    >
                                        <span class="truncate">@{{ grand.name }}</span>
                                        <span class="icon-arrow-right text-xs text-zinc-400"></span>
                                    </a>
                                </div>
                            </template>

                            <p v-else class="text-sm text-zinc-500">
                                @lang('shop::app.components.layouts.header.no-category-found')
                            </p>
                        </div>
                    </div>

                    <p v-else class="text-sm text-zinc-500">
                        @lang('shop::app.components.layouts.header.no-category-found')
                    </p>
                </template>

            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-collapsible-categories', {
            template: '#v-collapsible-categories-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                    activeCategoryId: null,
                    activeSubcategoryId: null,
                };
            },

            computed: {
                activeCategory() {
                    return this.categories.find(category => category.id === this.activeCategoryId) || null;
                },

                activeSubcategory() {
                    if (! this.activeCategory) {
                        return null;
                    }

                    return this.activeCategory.children.find(child => child.id === this.activeSubcategoryId) || null;
                },
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            const mapOpen = (node) => ({
                                ...node,
                                children: (node.children || []).map(mapOpen),
                            });

                            this.categories = (response.data.data || []).map(mapOpen);
                            this.activeCategoryId = this.categories.length ? this.categories[0].id : null;
                            this.activeSubcategoryId = this.activeCategory && this.activeCategory.children.length
                                ? this.activeCategory.children[0].id
                                : null;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error(error);
                            this.isLoading = false;
                        });
                },

                setActiveCategory(category) {
                    if (! category) {
                        return;
                    }

                    this.activeCategoryId = category.id;
                    this.activeSubcategoryId = category.children && category.children.length
                        ? category.children[0].id
                        : null;
                },

                setActiveSubcategory(subcategory) {
                    if (! subcategory) {
                        return;
                    }

                    this.activeSubcategoryId = subcategory.id;
                },
            },
        });
    </script>
@endPushOnce

@pushOnce('styles')
    <style>
        .category-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .category-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(63, 63, 70, 0.25);
            border-radius: 9999px;
        }

        .category-scroll:hover::-webkit-scrollbar-thumb {
            background-color: rgba(63, 63, 70, 0.45);
        }
    </style>
@endPushOnce
