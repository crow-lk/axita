{!! view_render_event('bagisto.shop.categories.view.filters.before') !!}

<!-- Desktop Filters Navigation -->
<div v-if="! isMobile">
    <!-- Filters Vue Component -->
    <v-filters
        @filter-applied="setFilters('filter', $event)"
    >
        <!-- Category Filter Shimmer Effect -->
        <x-shop::shimmer.categories.filters />
    </v-filters>
</div>

<!-- Mobile Filters Navigation -->
<div
    class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-zinc-200 bg-white px-5 ltr:left-0 rtl:right-0"
    v-if="isMobile"
>
    <!-- Filter Drawer -->
    <x-shop::drawer
        position="left"
        width="100%"
        ::is-active="isDrawerActive.filter"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-md:py-3"
                @click="isDrawerActive.filter = true"
            >
                <span class="icon-filter-1 text-2xl"></span>

                @lang('shop::app.categories.filters.filter')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">
                    @lang('shop::app.categories.filters.filters')
                </p>

                <p
                    class="cursor-pointer text-sm font-medium ltr:mr-[50px] rtl:ml-[50px]"
                    @click="clearFilters"
                >
                    @lang('shop::app.categories.filters.clear-all')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content>
            <!-- Filters Vue Component -->
            <v-filters
                @filter-applied="setFilters('filter', $event)"
            >
                <!-- Category Filter Shimmer Effect -->
                <x-shop::shimmer.categories.filters />
            </v-filters>
        </x-slot>
    </x-shop::drawer>

    <!-- Separator -->
    <span class="h-5 w-0.5 bg-zinc-200"></span>

    <!-- Sort Drawer -->
    <x-shop::drawer
        position="bottom"
        width="100%"
        ::is-active="isDrawerActive.toolbar"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-md:py-3"
                @click="isDrawerActive.toolbar = true"
            >
                <span class="icon-sort-1 text-2xl"></span>

                @lang('shop::app.categories.filters.sort')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">
                    @lang('shop::app.categories.filters.sort')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content class="!px-0">
            @include('shop::categories.toolbar')
        </x-slot>
    </x-shop::drawer>
</div>

{!! view_render_event('bagisto.shop.categories.view.filters.after') !!}

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
            <div class="flex flex-wrap items-center gap-4 py-4">
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
                        class="flex items-center gap-2 rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                    >
                        <span>@{{ filter.name }}</span>
                        <span class="icon-arrow-down text-xs transition-transform duration-200"
                              :class="{'transform rotate-180': activeDropdown === filterIndex}"></span>
                    </button>

                    <!-- Dropdown Content -->
                    <div
                        v-show="activeDropdown === filterIndex"
                        @click.stop
                        class="absolute z-10 mt-1 w-56 rounded-md bg-white shadow-lg ring-1 ring-Orange ring-opacity-5 focus:outline-none"
                        style="min-inline-size: 200px; max-block-size: 300px; overflow-y: auto; border: 1px solid orange;"
                    >
                        <v-filter-item
                            :ref="'filterItem_' + filterIndex"
                            :filter="filter"
                            @values-applied="applyFilter(filter, $event)"
                            class="p-3"
                        ></v-filter-item>
                    </div>
                </div>
                 <!-- Clear Filters Button -->
                 <button @click="clearFilters"
    class="ml-4 px-4 py-2 bg-navyBlue text-white rounded hover:bg-orange-700 text-sm font-medium flex items-center gap-2">
                        <span class="icon-filter-1 text-xl"></span>
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
            <ul v-else class="space-y-2">
                <li
                    :key="option.id"
                    v-for="(option, optionIndex) in filter.options"
                >
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            :id="'option_' + option.id + '_' + filter.code"
                            class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                            :value="option.id"
                            v-model="appliedValues"
                            @change="applyValue"
                        />
                        <label
                            :for="'option_' + option.id + '_' + filter.code"
                            class="ml-2 text-sm text-gray-700"
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
