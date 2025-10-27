{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<!-- Main Header -->
<div class="main-header bg-white border-b border-gray-200 w-full">
    <!-- Top Row: Logo, Search Bar, Contact -->
    <div class="flex items-center justify-between gap-6 px-8 py-1">
        <!-- Left Section: Logo and Brand Info -->
        <div class="logo-section flex items-center gap-4 min-w-fit">
            <div class="text-left">
                <a
                    href="{{ route('shop.home.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.bagisto')"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        width="100"
                        height="22"
                        alt="{{ config('app.name') }}"
                    >
                </a>
                <h1 class="text-base font-bold transition-colors duration-300">Computers Online Store</h1>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.search_bar.before') !!}

        <!-- Center Section: Search Bar -->
        <div class="flex-grow max-w-2xl mx-6">
            <v-search-autocomplete></v-search-autocomplete>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.search_bar.after') !!}

        <!-- Right Section: Contact Info & Social Media -->
        <div class="contact-info flex flex-col items-end text-right gap-1 min-w-fit">
            <div class="social-icons flex items-center justify-end gap-2.5">
                <a href="https://www.facebook.com/axitacomputers" class="text-blue-600 hover:text-blue-800 text-sm transition-all duration-300" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.tiktok.com/@axita.galle" class="text-black hover:text-gray-700 text-sm transition-all duration-300" aria-label="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.instagram.com/axita_computer/" class="text-pink-500 hover:text-pink-700 text-sm transition-all duration-300" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <p class="text-xs font-semibold text-[#e85805]">
                <a href="tel:+94771284323" class="hover:text-[#d14805]">+94 77 128 4323</a>
            </p>
        </div>
    </div>

    <!-- Bottom Row: Tagline -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 py-1 px-8 overflow-hidden">
        <div class="flex items-center justify-center">
            <a href="http://axita.winsoft.site/repair-status" class="text-xs font-semibold text-gray-700 hover:text-[#e85805] transition-colors inline-block animate-slide">
                Check your service status from here.
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes slide {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    
    .animate-slide {
        animation: slide 15s linear infinite;
        white-space: nowrap;
    }
    
    .animate-slide:hover {
        animation-play-state: paused;
    }
</style>

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