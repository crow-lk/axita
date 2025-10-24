{!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

<v-shipping-methods
                    ref="shippingComponent"
                    :methods="cart.shipping_rates || null"
                    :cart="cart"
                    @processing="stepForward"
                    @processed="stepProcessed"
                    @save-address-first="handleSaveAddressFirst">
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="mb-7 max-md:mb-0">
            <h2 class="mb-4 text-2xl font-medium max-md:text-base">
                @lang('shop::app.checkout.onepage.shipping.shipping-method')
            </h2>

            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <div class="flex flex-wrap gap-8 max-md:gap-4 max-sm:gap-2.5">
                    <template v-for="method in methods">
                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

                        <div
                            class="relative max-w-[218px] select-none max-md:max-w-full max-md:flex-auto"
                            v-for="rate in method.rates"
                        >
                            <input
                                type="radio"
                                name="shipping_method"
                                :id="rate.method"
                                :value="rate.method"
                                class="hidden peer"
                                v-model="selectedShippingMethod"
                                @change="store(rate.method)"
                            >

                            <label
                                class="absolute text-2xl cursor-pointer icon-radio-unselect peer-checked:icon-radio-select top-5 text-navyBlue ltr:right-5 rtl:left-5"
                                :for="rate.method"
                            >
                            </label>

                            <label
                                class="block cursor-pointer rounded-xl border border-zinc-200 p-5 max-sm:flex max-sm:gap-4 max-sm:rounded-lg max-sm:px-4 max-sm:py-2.5"
                                :for="rate.method"
                            >
                                <span class="text-6xl icon-flate-rate text-navyBlue max-sm:text-5xl"></span>

                                <div>
                                    <p class="mt-1.5 text-2xl font-semibold max-md:text-base">
                                        @{{ rate.base_formatted_price }}
                                    </p>

                                    <p class="mt-2.5 text-xs font-medium max-md:mt-1 max-sm:mt-0 max-sm:font-normal max-sm:text-zinc-500">
                                        <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                    </p>
                                </div>
                            </label>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}
                    </template>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
                cart: {
                    type: Object,
                    default: () => ({}),
                },
            },

            emits: ['processing', 'processed'],

            data() {
                return {
                    autoSelectedOnce: false,
                    selectedShippingMethod: null,
                };
            },

            mounted() {
                this.initializeShippingMethod();
            },

            watch: {
                methods: {
                    handler(newMethods) {
                        // Initialize shipping method when methods become available
                        this.initializeShippingMethod();
                    },
                    deep: true,
                },
            },

            methods: {
                initializeShippingMethod() {
                    if (!this.methods || Object.keys(this.methods).length === 0 || this.autoSelectedOnce) {
                        return;
                    }

                    // Check if shipping method is already selected in cart
                    if (this.cart && this.cart.shipping_method) {
                        this.selectedShippingMethod = this.cart.shipping_method;
                        this.autoSelectedOnce = true;
                        return;
                    }

                    {{-- // Auto-select first shipping method
                    const firstMethodKey = Object.keys(this.methods)[0];
                    const firstMethod = this.methods[firstMethodKey];

                    if (firstMethod && firstMethod.rates && firstMethod.rates.length > 0) {
                        const firstRate = firstMethod.rates[0];
                        this.selectedShippingMethod = firstRate.method;
                        this.autoSelectedOnce = true;
                        this.store(firstRate.method);
                    }

                    // collectTotals after auto-selection
                    this.$emit('processing', 'shipping'); --}}
                },

                store(selectedMethod) {
                    // Check if billing address is saved
                    if (!this.cart.billing_address) {
                        // Request parent to save address first
                        this.$emit('save-address-first', selectedMethod);
                        return;
                    }

                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
