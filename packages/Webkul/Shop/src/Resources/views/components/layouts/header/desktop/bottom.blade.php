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
                <div class="w-[520px] overflow-hidden rounded-3xl border border-white/40 bg-gradient-to-br from-white via-zinc-50 to-white/70 shadow-[0_24px_60px_rgba(15,23,42,0.08)] backdrop-blur-lg transition-all duration-300" @click.stop>
                    <div class="flex items-center justify-between border-b border-white/60 px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-zinc-800">Explore categories</p>
                            <p class="text-xs text-zinc-500">Jump straight into what you love</p>
                        </div>
                    </div>

                    <div class="max-h-[60vh] overflow-y-auto px-4 pb-5 pt-4">
                        <v-collapsible-categories>
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
        <div>
            <div v-if="isLoading" class="grid gap-2">
                <span class="shimmer h-6 w-28 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-32 rounded" role="presentation"></span>
                <span class="shimmer h-6 w-24 rounded" role="presentation"></span>
            </div>

            <div v-else class="grid gap-3">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="rounded-2xl border border-transparent bg-white/80 p-4 shadow-sm transition-all duration-200 hover:border-zinc-300 hover:bg-white"
                    @mouseenter="openOnHover(category)"
                    @mouseleave="closeOnHover(category)"
                >
                    <template v-if="category.children.length">
                        <div class="flex items-center justify-between gap-3">
                            <button
                                type="button"
                                @click="toggle(category)"
                                class="text-left text-base font-semibold text-zinc-900 transition-colors duration-200 hover:text-zinc-700 focus-visible:outline-none"
                                :aria-expanded="category.isOpen"
                            >
                                @{{ category.name }}
                            </button>

                            <button
                                type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-900/10 text-xl text-zinc-500 transition-all duration-200 hover:bg-zinc-900/15 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-700/30"
                                @click.stop="toggle(category)"
                                :aria-label="'Toggle ' + category.name"
                                :aria-expanded="category.isOpen"
                            >
                                <span
                                    class="transition-transform duration-200"
                                    :class="{
                                        'icon-arrow-down -rotate-180 text-zinc-900': category.isOpen,
                                        'icon-arrow-right rotate-0 text-zinc-500': !category.isOpen
                                    }"
                                ></span>
                            </button>
                        </div>

                        <transition name="collapsible">
                            <div v-if="category.isOpen" class="mt-3 space-y-3">
                                <div class="flex justify-end">
                                    <a :href="category.url" class="inline-flex items-center gap-2 rounded-full border border-zinc-900/20 bg-zinc-900/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition-all duration-200 hover:bg-zinc-900 hover:text-white">
                                        <span>View all</span>
                                        <span class="icon-arrow-right text-sm"></span>
                                    </a>
                                </div>

                                <div
                                    v-for="second in category.children"
                                    :key="second.id"
                                    class="rounded-xl border border-zinc-200 bg-white p-3 shadow-sm transition-all duration-200 hover:border-zinc-300"
                                    @mouseenter="openOnHover(second)"
                                    @mouseleave="closeOnHover(second)"
                                >
                                    <template v-if="second.children.length">
                                        <div class="flex items-center justify-between gap-3">
                                            <button
                                                type="button"
                                                @click="toggle(second)"
                                                class="text-left text-sm font-semibold text-zinc-800 transition-colors duration-200 hover:text-zinc-600 focus-visible:outline-none"
                                                :aria-expanded="second.isOpen"
                                            >
                                                @{{ second.name }}
                                            </button>

                                            <button
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-900/10 text-lg text-zinc-500 transition-all duration-200 hover:bg-zinc-900/15 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-700/20"
                                                @click.stop="toggle(second)"
                                                :aria-label="'Toggle ' + second.name"
                                                :aria-expanded="second.isOpen"
                                            >
                                                <span
                                                    class="transition-transform duration-200"
                                                    :class="{
                                                        'icon-arrow-down -rotate-180 text-zinc-900': second.isOpen,
                                                        'icon-arrow-right rotate-0 text-zinc-500': !second.isOpen
                                                    }"
                                                ></span>
                                            </button>
                                        </div>

                                        <transition name="collapsible">
                                            <div v-if="second.isOpen" class="mt-2 space-y-1 ltr:pl-3 rtl:pr-3">
                                                <div class="mb-1 flex justify-end">
                                                    <a :href="second.url" class="inline-flex items-center gap-2 text-xs font-medium text-zinc-700 transition-colors duration-200 hover:text-zinc-900">
                                                        <span>View all</span>
                                                        <span class="icon-arrow-right text-xs"></span>
                                                    </a>
                                                </div>

                                                <ul v-if="second.children.length" class="space-y-1 text-sm text-zinc-600">
                                                    <li v-for="third in second.children" :key="third.id">
                                                        <a :href="third.url" class="transition-colors duration-200 hover:text-zinc-900">@{{ third.name }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </transition>
                                    </template>

                                    <template v-else>
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-semibold text-zinc-700">@{{ second.name }}</span>
                                            <a :href="second.url" class="inline-flex items-center gap-2 text-xs font-medium text-zinc-700 transition-colors duration-200 hover:text-zinc-900">
                                                <span>View all</span>
                                                <span class="icon-arrow-right text-xs"></span>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </transition>
                    </template>

                    <template v-else>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-base font-semibold text-zinc-900">@{{ category.name }}</span>
                            <a :href="category.url" class="inline-flex items-center gap-2 rounded-full border border-zinc-900/20 bg-zinc-900/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition-all duration-200 hover:bg-zinc-900 hover:text-white">
                                <span>View all</span>
                                <span class="icon-arrow-right text-sm"></span>
                            </a>
                        </div>
                    </template>
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
                    hoverTimeouts: {},
                };
            },

            beforeDestroy() {
                Object.values(this.hoverTimeouts).forEach(timeout => clearTimeout(timeout));

                this.hoverTimeouts = {};
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
                    if (! (node.children && node.children.length)) {
                        return;
                    }

                    this.clearHoverTimeout(node);

                    node.isOpen = !node.isOpen;
                },

                openOnHover(node) {
                    if (! (node.children && node.children.length)) {
                        return;
                    }

                    this.clearHoverTimeout(node);

                    node.isOpen = true;
                },

                closeOnHover(node) {
                    if (! (node.children && node.children.length)) {
                        return;
                    }

                    this.clearHoverTimeout(node);

                    this.hoverTimeouts[node.id] = setTimeout(() => {
                        node.isOpen = false;

                        delete this.hoverTimeouts[node.id];
                    }, 120);
                },

                clearHoverTimeout(node) {
                    const timeout = this.hoverTimeouts[node.id];

                    if (timeout) {
                        clearTimeout(timeout);

                        delete this.hoverTimeouts[node.id];
                    }
                },
            },
        });
    </script>
@endPushOnce

@pushOnce('styles')
    <style>
        .collapsible-enter-active,
        .collapsible-leave-active {
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .collapsible-enter-from,
        .collapsible-leave-to {
            opacity: 0;
            transform: translateY(-4px);
            max-height: 0;
        }

        .collapsible-enter-to,
        .collapsible-leave-from {
            opacity: 1;
            transform: translateY(0);
            max-height: 600px;
        }
    </style>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
