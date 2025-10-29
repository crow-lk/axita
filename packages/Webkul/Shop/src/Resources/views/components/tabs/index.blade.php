@props(['position' => 'left'])

<v-tabs
    position="{{ $position }}"
    {{ $attributes }}
>
    <x-shop::shimmer.tabs />
</v-tabs>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tabs-template"
    >
        <div class="space-y-10">
            <div class="container px-5">
                <div class="mx-auto max-w-5xl rounded-full border border-zinc-100 bg-white/80 p-1 shadow-sm shadow-zinc-200/40 backdrop-blur-md">
                    <div
                        class="flex flex-wrap items-center gap-1.5"
                        :style="positionStyles"
                    >
                        <div
                            role="button"
                            tabindex="0"
                            v-for="tab in tabs"
                            class="group relative flex cursor-pointer items-center rounded-full px-6 py-3 text-sm font-medium text-zinc-500 transition-all duration-200 ease-out hover:text-zinc-900 hover:shadow-sm max-md:px-4 max-md:py-2.5 max-sm:px-3 max-sm:text-xs"
                            :class="{
                                'bg-zinc-900 text-white shadow-lg shadow-zinc-400/50 hover:text-white': tab.isActive,
                                'bg-transparent': ! tab.isActive,
                            }"
                            :id="tab.$attrs.id + '-button'"
                            @click="change(tab)"
                        >
                            @{{ tab.title }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5">
                {{ $slot }}
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tabs', {
            template: '#v-tabs-template',

            props: ['position'],

            data() {
                return {
                    tabs: []
                }
            },

            computed: {
                positionStyles() {
                    return [
                        `justify-content: ${this.position}`
                    ];
                },
            },

            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.title == selectedTab.title);
                    });
                },
            },
        });
    </script>
@endPushOnce
