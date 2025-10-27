{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[56px] w-full items-center border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8 relative">
    <!-- Browse Categories (only) -->
    <div class="flex items-center gap-x-6 max-[1180px]:gap-x-4">
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

    <!-- Right side intentionally omitted (icons are in top bar) -->
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
