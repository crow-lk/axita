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
                <div class="w-[920px] max-w-[92vw] overflow-hidden rounded-3xl border border-white/50 bg-white shadow-[0_28px_60px_rgba(15,23,42,0.12)] backdrop-blur-md transition-all duration-200" @click.stop>
                    <div class="border-b border-zinc-200 px-6 py-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.12em] text-zinc-500">All Departments</p>
                        <p class="text-lg font-semibold text-zinc-800">Browse categories</p>
                    </div>

                    <v-collapsible-categories />
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

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
