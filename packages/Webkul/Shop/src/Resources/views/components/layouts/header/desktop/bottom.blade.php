{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[56px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8 relative">
    <!-- Left: Browse Categories (collapsible) -->
    <div class="flex items-center gap-x-6 max-[1180px]:gap-x-4 w-1/3">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <x-shop::dropdown position="bottom-left">
            <x-slot:toggle>
                <button class="flex items-center gap-2 rounded-md border border-zinc-300 bg-zinc-100 px-3 py-1.5 text-sm font-medium hover:bg-zinc-200">
                    <span class="icon-hamburger text-xl"></span>
                    <span>Browse Categories</span>
                </button>
            </x-slot:toggle>

            <x-slot:content class="!p-0">
                <div class="w-[420px] max-h-[70vh] overflow-y-auto p-3" @click.stop>
                    <v-collapsible-categories>
                        <div class="grid gap-2">
                            <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                            <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                            <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
                        </div>
                    </v-collapsible-categories>
                </div>
            </x-slot:content>
        </x-shop::dropdown>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Center: Search -->
    <div class="flex justify-center items-center w-1/3">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar Container -->
        <div class="relative w-full max-w-[445px]">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex w-full items-center"
                role="search"
            >
                <label
                    for="organic-search"
                    class="sr-only"
                >
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-xl ltr:left-3 rtl:right-3"></div>

                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full rounded-lg border border-transparent bg-zinc-100 px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                    minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                    maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.search-text')"
                    aria-required="true"
                    pattern="[^\\]+"
                    required
                >

                <button
                    type="submit"
                    class="hidden"
                    aria-label="@lang('shop::app.components.layouts.header.submit')"
                >
                </button>

                @if (core()->getConfigData('catalog.products.settings.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

    </div>

    <!-- Right: intentionally empty (icons moved to top bar) -->
    <div class="flex items-center justify-end gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8 w-1/3"></div>
</div>

@pushOnce('scripts')
    <!-- Collapsible Categories Template -->
    <script type="text/x-template" id="v-collapsible-categories-template">
        <div>
            <div v-if="isLoading" class="grid gap-2">
                <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
            </div>

            <div v-else class="grid">
                <div v-for="category in categories" :key="category.id" class="border-b border-zinc-200 py-2">
                    <div class="flex items-center justify-between">
                        <a href="#" @click.prevent="toggle(category)" class="font-medium">@{{ category.name }}</a>
                        <span class="text-xl cursor-pointer" :class="{ 'icon-arrow-down': category.isOpen, 'icon-arrow-right': !category.isOpen }" @click.stop.prevent="toggle(category)"></span>
                    </div>

                    <div v-if="category.isOpen" class="mt-2 ltr:pl-3 rtl:pr-3">
                        <div class="mb-2 flex justify-end">
                            <a :href="category.url" class="text-xs text-navyBlue hover:underline">View all</a>
                        </div>
                        <template v-if="category.children.length">
                            <div v-for="second in category.children" :key="second.id" class="py-1">
                                <div class="flex items-center justify-between">
                                    <a href="#" @click.prevent="toggle(second)" class="text-sm font-medium">@{{ second.name }}</a>
                                    <span class="text-lg cursor-pointer" :class="{ 'icon-arrow-down': second.isOpen, 'icon-arrow-right': !second.isOpen }" @click.stop.prevent="toggle(second)"></span>
                                </div>

                                <div v-if="second.isOpen" class="mt-1 ltr:pl-3 rtl:pr-3">
                                    <div class="mb-1 flex justify-end">
                                        <a :href="second.url" class="text-xs text-navyBlue hover:underline">View all</a>
                                    </div>
                                    <ul v-if="second.children.length">
                                        <li v-for="third in second.children" :key="third.id" class="py-1 text-sm text-zinc-600">
                                            <a :href="third.url">@{{ third.name }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </template>

                        <span v-else class="text-sm text-zinc-500">@lang('shop::app.components.layouts.header.no-category-found')</span>
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
                };
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
                                isOpen: false,
                                children: (node.children || []).map(mapOpen),
                            });

                            this.categories = (response.data.data || []).map(mapOpen);
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error(error);
                            this.isLoading = false;
                        });
                },

                toggle(node) {
                    node.isOpen = !node.isOpen;
                },
            },
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
