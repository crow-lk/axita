@props([
    'position' => 'bottom-right',
    'trigger' => 'click',
    'openDelay' => 150,
    'closeDelay' => 200,
])

<v-dropdown
    position="{{ $position }}"
    trigger="{{ $trigger }}"
    open-delay="{{ $openDelay }}"
    close-delay="{{ $closeDelay }}"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    @isset($toggle)
        {{ $toggle }}

        <template v-slot:toggle>
            {{ $toggle }}
        </template>
    @endisset

    @isset($content)
        <template v-slot:content>
            <div {{ $content->attributes->merge(['class' => 'p-5']) }}>
                {{ $content }}
            </div>
        </template>
    @endisset

    @isset($menu)
        <template v-slot:menu>
            <ul {{ $menu->attributes->merge(['class' => 'py-4']) }}>
                {{ $menu }}
            </ul>
        </template>
    @endisset
</v-dropdown>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dropdown-template"
    >
        <div
            class="v-dropdown-wrapper"
            @mouseenter="handleMouseEnter"
            @mouseleave="handleMouseLeave"
        >
            <div
                class="select-none"
                ref="toggleBlock"
                @click="handleToggleClick"
            >
                <slot name="toggle">Toggle</slot>
            </div>

            <transition
                tag="div"
                name="dropdown"
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="scale-95 transform opacity-0"
                enter-to-class="scale-100 transform opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="scale-100 transform opacity-100"
                leave-to-class="scale-95 transform opacity-0"
            >
                <div
                    class="absolute z-20 w-max overflow-hidden rounded-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)] max-md:rounded-lg"
                    :style="positionStyles"
                    v-show="isActive"
                >
                    <slot name="content"></slot>

                    <slot name="menu"></slot>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-dropdown', {
            template: '#v-dropdown-template',

            props: {
                position: String,

                closeOnClick: {
                    type: Boolean,
                    required: false,
                    default: true
                },

                trigger: {
                    type: String,
                    default: 'click',
                    validator: value => ['click', 'hover'].includes(value),
                },

                openDelay: {
                    type: [Number, String],
                    default: 150,
                },

                closeDelay: {
                    type: [Number, String],
                    default: 200,
                },
            },

            data() {
                return {
                    toggleBlockWidth: 0,

                    toggleBlockHeight: 0,

                    isActive: false,

                    openTimeout: null,

                    closeTimeout: null,
                };
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            mounted() {
                this.toggleBlockWidth = this.$refs.toggleBlock.clientWidth;

                this.toggleBlockHeight = this.$refs.toggleBlock.clientHeight;
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);

                this.clearOpenTimeout();

                this.clearCloseTimeout();
            },

            computed: {
                positionStyles() {
                    switch (this.position) {
                        case 'bottom-left':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `top: ${this.toggleBlockHeight}px`,
                                'left: 0',
                            ];

                        case 'bottom-right':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `top: ${this.toggleBlockHeight}px`,
                                'right: 0',
                            ];

                        case 'top-left':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `bottom: ${this.toggleBlockHeight}px`,
                                'left: 0',
                            ];

                        case 'top-right':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `bottom: ${this.toggleBlockHeight}px`,
                                'right: 0',
                            ];

                        default:
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `top: ${this.toggleBlockHeight}px`,
                                'left: 0',
                            ];
                    }
                },
            },

            methods: {
                handleToggleClick() {
                    if (this.trigger === 'click') {
                        this.toggle();
                    }
                },

                handleMouseEnter() {
                    if (this.trigger !== 'hover') {
                        return;
                    }

                    this.clearOpenTimeout();

                    this.clearCloseTimeout();

                    this.openTimeout = setTimeout(() => {
                        this.open();
                    }, Number(this.openDelay) || 0);
                },

                handleMouseLeave() {
                    if (this.trigger !== 'hover') {
                        return;
                    }

                    this.clearOpenTimeout();

                    this.clearCloseTimeout();

                    this.closeTimeout = setTimeout(() => {
                        this.close();
                    }, Number(this.closeDelay) || 0);
                },

                ensureDimensions() {
                    if (this.toggleBlockWidth === 0) {
                        this.toggleBlockWidth = this.$refs.toggleBlock.clientWidth;
                    }

                    if (this.toggleBlockHeight === 0) {
                        this.toggleBlockHeight = this.$refs.toggleBlock.clientHeight;
                    }
                },

                open() {
                    this.clearOpenTimeout();

                    this.clearCloseTimeout();

                    this.ensureDimensions();

                    this.isActive = true;
                },

                close() {
                    this.clearOpenTimeout();

                    this.clearCloseTimeout();

                    this.isActive = false;
                },

                clearOpenTimeout() {
                    if (this.openTimeout) {
                        clearTimeout(this.openTimeout);

                        this.openTimeout = null;
                    }
                },

                clearCloseTimeout() {
                    if (this.closeTimeout) {
                        clearTimeout(this.closeTimeout);

                        this.closeTimeout = null;
                    }
                },

                toggle() {
                    if (this.isActive) {
                        this.close();
                    } else {
                        this.open();
                    }
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target) || (this.closeOnClick && this.$el.children[1].contains(e.target))) {
                        this.close();
                    }
                },
            },
        });
    </script>
@endPushOnce
