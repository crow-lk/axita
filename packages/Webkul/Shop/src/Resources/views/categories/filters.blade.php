{!! view_render_event('bagisto.shop.categories.view.filters.before') !!}

<!-- Filters Navigation -->
<div>
    <!-- Filters Vue Component -->
    <v-filters
        @filter-applied="setFilters('filter', $event)"
    >
        <!-- Category Filter Shimmer Effect -->
        <x-shop::shimmer.categories.filters />
    </v-filters>
</div>

<!-- Mobile Utility Navigation -->
<div
    v-if="isMobile"
    class="pointer-events-none fixed inset-x-0 bottom-4 z-20 px-4 text-white"
>
    <nav class="pointer-events-auto mx-auto flex max-w-[280px] items-center justify-between gap-5 rounded-2xl border border-zinc-200 bg-white/90 px-6 py-4 text-zinc-900 shadow-[0_16px_30px_rgba(17,24,39,0.15)] backdrop-blur">
        <a
            href="{{ route('shop.home.index') }}"
            class="flex items-center justify-center text-zinc-700 transition-all duration-200 hover:-translate-y-0.5 hover:text-black"
            aria-label="Home"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                class="h-6 w-6"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="m3 10 9-7 9 7"></path>
                <path d="M5 10v10h5v-5h4v5h5V10"></path>
            </svg>
        </a>

        <a
            href="{{ route('shop.customers.account.wishlist.index') }}"
            class="flex items-center justify-center text-zinc-700 transition-all duration-200 hover:-translate-y-0.5 hover:text-black"
            aria-label="Wishlist"
        >
            <span class="icon-heart text-2xl"></span>
        </a>

        <a
            href="{{ route('shop.checkout.cart.index') }}"
            class="flex items-center justify-center text-zinc-700 transition-all duration-200 hover:-translate-y-0.5 hover:text-black"
            aria-label="Cart"
        >
            <span class="icon-cart text-2xl"></span>
        </a>

        @auth('customer')
            <a
                href="{{ route('shop.customers.account.profile.index') }}"
                class="flex items-center justify-center text-zinc-700 transition-all duration-200 hover:-translate-y-0.5 hover:text-black"
                aria-label="Account"
            >
                <span class="icon-users text-2xl"></span>
            </a>
        @else
            <a
                href="{{ route('shop.customer.session.create') }}"
                class="flex items-center justify-center text-zinc-700 transition-all duration-200 hover:-translate-y-0.5 hover:text-black"
                aria-label="Account"
            >
                <span class="icon-users text-2xl"></span>
            </a>
        @endauth
    </nav>
</div>

{!! view_render_event('bagisto.shop.categories.view.filters.after') !!}

@pushOnce('styles')
    <style>
        /* Modern Filter Dropdown Animations */
        [v-show] {
            animation: fadeInDown 0.3s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar for Dropdowns */
        .absolute[style*="overflow-y: auto"]::-webkit-scrollbar {
            width: 8px;
        }

        .absolute[style*="overflow-y: auto"]::-webkit-scrollbar-track {
            background: #fef3e2;
            border-radius: 10px;
        }

        .absolute[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #fb923c, #f97316);
            border-radius: 10px;
            transition: background 0.3s;
        }

        .absolute[style*="overflow-y: auto"]::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #f97316, #ea580c);
        }

        /* Checkbox Custom Styling */
        input[type="checkbox"]:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <!-- Filters Vue template -->
    <script
        type="text/x-template"
        id="v-filters-template"
    >
        <!-- Filter Shimmer Effect -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.filters />
        </template>

        <!-- Filters Container -->
        <template v-else>
            <div class="flex flex-wrap items-center gap-3 py-4">
                <!-- Price Filter (special display) -->
                <template v-if="hasPriceFilter">
                    <v-filter-item
                        ref="priceFilterComponent"
                        :filter="priceFilter"
                        @values-applied="applyFilter(priceFilter, $event)"
                    ></v-filter-item>
                </template>

                <!-- Other Filters with Dropdowns -->
                <div
                    class="relative"
                    v-for="(filter, filterIndex) in nonPriceFilters"
                    :key="filterIndex"
                >
                    <button
                        @click.stop="toggleDropdown(filterIndex)"
                        class="group relative flex items-center gap-2.5 rounded-lg border-2 border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-300 ease-in-out hover:border-orange-400 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                        :class="{'border-orange-500 bg-orange-50': activeDropdown === filterIndex}"
                    >
                        <span class="transition-colors duration-200" :class="{'text-orange-600': activeDropdown === filterIndex}">@{{ filter.name }}</span>
                        <span class="icon-arrow-down text-xs transition-all duration-300"
                              :class="{
                                  'transform rotate-180 text-orange-600': activeDropdown === filterIndex,
                                  'group-hover:text-orange-500': activeDropdown !== filterIndex
                              }"></span>
                    </button>

                    <!-- Dropdown Content -->
                    <div
                        v-show="activeDropdown === filterIndex"
                        @click.stop
                        class="absolute z-20 mt-2 w-64 rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 backdrop-blur-sm transition-all duration-200 ease-out"
                        style="min-inline-size: 240px; max-block-size: 350px; overflow-y: auto; border: 2px solid #fb923c;"
                    >
                        <!-- Dropdown Header -->
                        <div class="sticky top-0 z-10 bg-gradient-to-r from-orange-50 to-orange-100 px-4 py-3 border-b-2 border-orange-200 rounded-t-xl">
                            <h3 class="text-sm font-bold text-orange-900">@{{ filter.name }}</h3>
                        </div>
                        
                        <!-- Dropdown Items -->
                        <div class="px-3 py-2">
                            <v-filter-item
                                :ref="'filterItem_' + filterIndex"
                                :filter="filter"
                                @values-applied="applyFilter(filter, $event)"
                            ></v-filter-item>
                        </div>
                    </div>
                </div>
                 <!-- Clear Filters Button -->
                 <button @click="clearFilters"
                    class="ml-auto flex items-center gap-2.5 rounded-lg border-2 border-orange-500 bg-white px-5 py-2.5 text-sm font-semibold text-orange-600 shadow-sm transition-all duration-300 ease-in-out hover:bg-orange-500 hover:text-white hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        <span class="icon-filter-1 text-lg"></span>
                        @lang('shop::app.categories.filters.clear-all')
                    </button>
            </div>
        </template>

    </script>

    <!-- Filter Item Vue template -->
    <script
        type="text/x-template"
        id="v-filter-item-template"
    >
        <template v-if="filter.type === 'price' || filter.options.length">
            <!-- Price Filter -->
            <template v-if="filter.type === 'price'">
                <div class="flex items-center gap-4">
                    <!-- <span class="whitespace-nowrap text-sm font-medium">@lang('shop::app.categories.filters.price-range')</span> -->
                    <v-price-filter
                        :key="refreshKey"
                        :default-price-range="appliedValues"
                        @set-price-range="applyValue($event)"
                    ></v-price-filter>
                </div>
            </template>

            <!-- Other Filters -->
            <ul v-else class="space-y-1.5">
                <li
                    :key="option.id"
                    v-for="(option, optionIndex) in filter.options"
                >
                    <div class="group flex items-center gap-3 rounded-lg p-2.5 transition-all duration-200 hover:bg-orange-50">
                        <input
                            type="checkbox"
                            :id="'option_' + option.id + '_' + filter.code"
                            class="h-5 w-5 rounded-md border-2 border-gray-300 text-orange-600 transition-all duration-200 focus:ring-orange-500 focus:ring-2 focus:ring-offset-1 cursor-pointer hover:border-orange-400 checked:border-orange-600 checked:bg-orange-600"
                            :value="option.id"
                            v-model="appliedValues"
                            @change="applyValue"
                        />
                        <label
                            :for="'option_' + option.id + '_' + filter.code"
                            class="flex-1 text-sm font-medium text-gray-700 cursor-pointer transition-colors duration-200 group-hover:text-orange-700 select-none"
                        >
                            @{{ option.name }}
                        </label>
                    </div>
                </li>
            </ul>
        </template>
    </script>

    <script
        type="text/x-template"
        id="v-price-filter-template"
    >
        <div>
            <!-- Price range filter shimmer -->
            <template v-if="isLoading">
                <x-shop::shimmer.range-slider />
            </template>

            <template v-else>
                <x-shop::range-slider
                    ::key="refreshKey"
                    default-type="price"
                    ::default-allowed-max-range="allowedMaxPrice"
                    ::default-min-range="minRange"
                    ::default-max-range="maxRange"
                    @change-range="setPriceRange($event)"
                />
            </template>
        </div>
    </script>

    <script type='module'>
        app.component('v-filters', {
            template: '#v-filters-template',

            data() {
                return {
                    isLoading: true,
                    activeDropdown: null,
                    filters: {
                        available: [],
                        applied: {},
                    },
                };
            },

            computed: {
                hasPriceFilter() {
                    return this.filters.available.some(filter => filter.type === 'price');
                },

                priceFilter() {
                    return this.filters.available.find(filter => filter.type === 'price');
                },

                nonPriceFilters() {
                    return this.filters.available.filter(filter => filter.type !== 'price');
                }
            },

            mounted() {
                this.getFilters();
                this.setFilters();

                // Close dropdowns when clicking outside
                document.addEventListener('click', this.closeAllDropdowns);
            },

            beforeDestroy() {
                document.removeEventListener('click', this.closeAllDropdowns);
            },

            methods: {
                toggleDropdown(index) {
                    this.activeDropdown = this.activeDropdown === index ? null : index;
                },

                closeAllDropdowns() {
                    this.activeDropdown = null;
                },

                getFilters() {
                    this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                            params: {
                                category_id: "{{ isset($category) ? $category->id : ''  }}",
                            }
                        })
                        .then((response) => {
                            this.isLoading = false;
                            this.filters.available = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        if (! ['sort', 'limit', 'mode'].includes(filter)) {
                            this.filters.applied[filter] = value.split(',');
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },

                applyFilter(filter, values) {
                    if (values && values.length) {
                        this.filters.applied[filter.code] = values;
                    } else {
                        delete this.filters.applied[filter.code];
                    }

                    this.$emit('filter-applied', this.filters.applied);
                },

                  clearFilters() {
                    this.filters.applied = {};

                    // Clear price filter
                     if (this.$refs.priceFilterComponent && this.$refs.priceFilterComponent.length > 0) {
                        this.$refs.priceFilterComponent[0].$data.appliedValues = null;
                        this.$refs.priceFilterComponent[0].refreshKey++; // Trigger refresh
                    }

                    // Clear other filters
                    Object.keys(this.$refs).forEach(key => {
                        if (key.startsWith('filterItem_') && this.$refs[key].length > 0) {
                            this.$refs[key][0].$data.appliedValues = [];
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                    this.activeDropdown = null;

                     // Clear the URL query parameters
                    window.location.search = "";
                },
            },
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter'],

            data() {
                return {
                    appliedValues: null,
                    refreshKey: 0,
                }
            },

            watch: {
                appliedValues() {
                    if (this.filter.code === 'price' && ! this.appliedValues) {
                        ++this.refreshKey;
                    }
                },
            },

            mounted() {
                if (this.filter.code === 'price') {
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code]?.join(',');
                    ++this.refreshKey;
                    return;
                }

                this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];
            },

            methods: {
                applyValue($event) {
                    if (this.filter.code === 'price') {
                        this.appliedValues = $event;
                        this.$emit('values-applied', this.appliedValues);
                        return;
                    }

                    this.$emit('values-applied', this.appliedValues);
                },
            },
        });

        app.component('v-price-filter', {
            template: '#v-price-filter-template',

            props: ['defaultPriceRange'],

            data() {
                return {
                    refreshKey: 0,
                    isLoading: true,
                    allowedMaxPrice: 100,
                    priceRange: this.defaultPriceRange ?? [0, 100].join(','),
                };
            },

            computed: {
                minRange() {
                    let priceRange = this.priceRange.split(',');
                    return priceRange[0];
                },

                maxRange() {
                    let priceRange = this.priceRange.split(',');
                    return priceRange[1];
                }
            },

            mounted() {
                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    this.$axios.get('{{ route("shop.api.categories.max_price", $category->id ?? '') }}')
                        .then((response) => {
                            this.isLoading = false;
                            if (response.data.data.max_price) {
                                this.allowedMaxPrice = response.data.data.max_price;
                            }

                            if (! this.defaultPriceRange) {
                                this.priceRange = [0, this.allowedMaxPrice].join(',');
                            }

                            ++this.refreshKey;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setPriceRange($event) {
                    this.priceRange = [$event.minRange, $event.maxRange].join(',');
                    this.$emit('set-price-range', this.priceRange);
                },
            },
        });
    </script>
@endPushOnce
