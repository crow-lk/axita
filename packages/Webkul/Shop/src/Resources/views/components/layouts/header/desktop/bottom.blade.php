{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex h-[56px] w-full items-center px-[60px] max-1180:px-8 relative bg-transparent border-b border-white/20">
    <!-- Browse Categories (only) -->
    <div class="flex items-center gap-x-6 max-[1180px]:gap-x-4">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <x-shop::dropdown
            position="bottom-left"
            trigger="hover"
            open-delay="120"
            close-delay="180"
        >
            <x-slot:toggle>
                <button class="group flex items-center gap-3 rounded-full bg-gradient-to-r from-black via-zinc-800 to-zinc-600 px-5 py-2 text-sm font-semibold uppercase tracking-wide text-white shadow-lg transition-all duration-200 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-700/40">
                    <span class="icon-hamburger text-xl transition-transform duration-200 group-hover:scale-110"></span>
                    <span>Browse Categories</span>
                    <span class="icon-arrow-down text-base transition-transform duration-200 group-hover:-rotate-180"></span>
                </button>
            </x-slot:toggle>

            <x-slot:content class="!p-0">
                <div class="w-[860px] max-w-[88vw] overflow-hidden rounded-3xl border border-white/40 bg-gradient-to-br from-white via-zinc-50 to-white/70 shadow-[0_24px_60px_rgba(15,23,42,0.08)] backdrop-blur-lg transition-all duration-300" @click.stop>
                    <div class="flex items-center justify-between border-b border-white/60 px-8 py-5">
                        <div>
                            <p class="text-sm font-semibold text-zinc-800">Explore categories</p>
                            <p class="text-xs text-zinc-500">Jump straight into what you love</p>
                        </div>
                    </div>

                    <div class="h-[70vh] overflow-hidden px-5 pb-6 pt-5">
                        <v-collapsible-categories class="flex h-full flex-col">
                            <div class="grid gap-3">
                                <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                                <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                                <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
                            </div>
                        </v-collapsible-categories>
                    </div>
                </div>
            </x-slot:content>
        </x-shop::dropdown>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right side intentionally omitted (icons are in top bar) -->
</div>

@pushOnce('scripts')
    <!-- Collapsible Categories Template -->
    <script type="text/x-template" id="v-collapsible-categories-template">
        <div class="flex h-full flex-col">
            <div v-if="isLoading" class="grid gap-2">
                <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
            </div>

            <div v-else class="flex h-full gap-6 overflow-hidden max-lg:flex-col">
                <div class="category-scroll flex w-[280px] flex-col gap-2 overflow-y-auto pr-2 max-lg:w-full max-lg:max-h-[40vh] max-lg:pr-0 max-lg:pb-2 lg:h-full" :style="{ scrollbarWidth: 'thin' }">
                    <button
                        v-for="category in categories"
                        :key="category.id"
                        type="button"
                        class="flex items-center justify-between rounded-2xl border border-transparent bg-white/70 px-5 py-3.5 text-left text-sm font-semibold text-zinc-600 transition-all duration-200 hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-700/20"
                        :class="{
                            'border-zinc-900/30 bg-zinc-900/10 text-zinc-900 shadow-sm': category.id === activeCategoryId
                        }"
                        @mouseenter="setActiveCategory(category)"
                        @focus="setActiveCategory(category)"
                    >
                        <span class="truncate">@{{ category.name }}</span>

                        <span
                            class="icon-arrow-right text-base transition-transform duration-200"
                            :class="{ '-rotate-45 text-zinc-900': category.id === activeCategoryId }"
                        ></span>
                    </button>
                </div>

                <div class="flex-1 flex flex-col overflow-hidden rounded-[32px] border border-zinc-200 bg-white/95 shadow-inner">
                    <div v-if="activeCategory" class="flex h-full flex-col">
                        <div class="flex items-start justify-between gap-3 border-b border-zinc-200 px-8 py-5">
                            <div>
                                <p class="text-xl font-semibold text-zinc-900">@{{ activeCategory.name }}</p>

                                <p v-if="activeCategory.children.length" class="mt-1 text-xs text-zinc-500">
                                    Explore featured collections and subcategories
                                </p>

                                <p v-else class="mt-1 text-xs text-zinc-500">@lang('shop::app.components.layouts.header.no-category-found')</p>
                            </div>

                            <div class="flex shrink-0 items-center gap-3">
                                <span class="rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-500">
                                    @{{ activeCategory.children.length }} subcategories
                                </span>

                                <a
                                    :href="activeCategory.url"
                                    class="inline-flex items-center gap-2 rounded-full border border-zinc-900/20 bg-zinc-900/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition-all duration-200 hover:bg-zinc-900 hover:text-white"
                                >
                                    <span>View all</span>
                                    <span class="icon-arrow-right text-sm"></span>
                                </a>
                            </div>
                        </div>

                        <div
                            v-if="activeCategory.children.length"
                            class="category-scroll flex-1 space-y-4 overflow-y-auto px-8 py-6 pr-5"
                            :style="{ scrollbarWidth: 'thin' }"
                        >
                            <div
                                v-for="second in activeCategory.children"
                                :key="second.id"
                                class="rounded-2xl border border-zinc-200 bg-white px-6 py-4 shadow-sm transition-all duration-200 hover:border-zinc-300 hover:shadow-lg"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <a
                                        :href="second.url"
                                        class="text-base font-semibold text-zinc-900 transition-colors duration-200 hover:text-zinc-700"
                                    >
                                        @{{ second.name }}
                                    </a>

                                    <a
                                        :href="second.url"
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-900/5 text-lg text-zinc-500 transition-all duration-200 hover:bg-zinc-900/10 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                        :aria-label="'View ' + second.name"
                                    >
                                        <span class="icon-arrow-right"></span>
                                    </a>
                                </div>

                                <div
                                    v-if="second.children && second.children.length"
                                    class="mt-4 space-y-1.5 text-sm text-zinc-600"
                                >
                                    <a
                                        v-for="third in second.children"
                                        :key="third.id"
                                        :href="third.url"
                                        class="flex items-center gap-2 rounded-lg px-3 py-1.5 transition-colors duration-150 hover:bg-zinc-100 hover:text-zinc-900"
                                    >
                                        <span aria-hidden="true" class="text-xs text-zinc-400">•</span>
                                        <span class="truncate">@{{ third.name }}</span>
                                    </a>
                                </div>

                                <p v-else class="mt-4 text-xs text-zinc-500">No additional subcategories</p>
                            </div>
                        </div>

                        <div
                            v-else
                            class="flex flex-1 items-center justify-center px-8 py-12 text-sm text-zinc-500"
                        >
                            @lang('shop::app.components.layouts.header.no-category-found')
                        </div>
                    </div>

                    <div class="flex h-full items-center justify-center px-8 py-12 text-sm text-zinc-500" v-else>
                        Select a category to preview its subcategories
                    </div>
                </div>
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
                };
            },

            computed: {
                activeCategory() {
                    return this.categories.find(category => category.id === this.activeCategoryId) || null;
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

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
