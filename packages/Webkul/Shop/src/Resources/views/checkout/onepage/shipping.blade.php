{!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

<v-shipping-methods
    :methods="shippingMethods"
    :current-step="currentStep"
    :selected-method="cart.shipping_method"
    @processing="stepForward"
    @processed="stepProcessed"
    @auto-selected="handleAutoSelectedShipping"
>
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="mb-6 max-md:mb-0">
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <!-- Accordion Blade Component -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="px-0 py-3 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-navyBlue max-md:text-base">
                                @lang('shop::app.checkout.onepage.shipping.shipping-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-6 !p-0 text-[13px] leading-5 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <div class="flex w-full flex-col gap-2 max-sm:gap-1.5">
                            <template v-for="method in methods">
                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

                                <div
                                    class="relative w-full select-none"
                                    v-for="rate in method.rates"
                                >
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="peer hidden"
                                        :checked="selectedRate === rate.method"
                                        @change="selectRate(rate.method)"
                                    >

                                    <label
                                        class="flex w-full cursor-pointer items-start gap-3 rounded-lg px-3 py-2 text-[13px] transition max-sm:flex-row max-sm:items-center"
                                        :class="selectedRate === rate.method ? 'bg-navyBlue/[0.08] text-navyBlue' : 'text-zinc-600 hover:bg-zinc-50'"
                                        :for="rate.method"
                                    >
                                        <span
                                            class="mt-1 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full border transition max-sm:mt-0"
                                            :class="selectedRate === rate.method ? 'border-navyBlue bg-navyBlue/10' : 'border-zinc-300 bg-transparent'"
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full transition"
                                                :class="selectedRate === rate.method ? 'bg-navyBlue' : 'bg-transparent'"
                                            ></span>
                                        </span>

                                        <div class="flex w-full items-center justify-between gap-4">
                                            <div class="flex items-start gap-3">
                                                <span class="icon-flate-rate text-2xl text-navyBlue"></span>

                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-navyBlue">@{{ rate.method_title }}</span>
                                                    <span class="text-xs text-zinc-500">@{{ rate.method_description }}</span>
                                                </div>
                                            </div>

                                            <span class="text-sm font-semibold text-navyBlue">@{{ rate.base_formatted_price }}</span>
                                        </div>
                                    </label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}
                            </template>
                        </div>
                    </x-slot>
                </x-shop::accordion>
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

                currentStep: {
                    type: String,
                    default: 'address',
                },

                selectedMethod: {
                    type: String,
                    default: null,
                },
            },

            emits: ['processing', 'processed', 'auto-selected'],

            data() {
                return {
                    selectedRate: this.selectedMethod,
                };
            },

            watch: {
                selectedMethod(value) {
                    this.selectedRate = value ?? null;

                    this.tryAutoSelectSingleRate();
                },

                methods: {
                    handler() {
                        this.tryAutoSelectSingleRate();
                    },
                    deep: true,
                },

                currentStep() {
                    this.tryAutoSelectSingleRate();
                },
            },

            mounted() {
                this.tryAutoSelectSingleRate();
            },

            methods: {
                selectRate(method) {
                    if (! method) {
                        return;
                    }

                    this.selectedRate = method;

                    this.store(method);
                },

                store(selectedMethod, options = {}) {
                    const { skipStepChange = false } = options;

                    if (! selectedMethod) {
                        return;
                    }

                    if (! skipStepChange) {
                        this.$emit('processing', 'payment');
                    }

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {    
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                const paymentMethods = response.data.payment_methods ?? [];

                                if (skipStepChange) {
                                    this.$emit('auto-selected', paymentMethods);
                                } else {
                                    this.$emit('processed', paymentMethods);
                                }
                            }
                        })
                        .catch(error => {
                            if (! skipStepChange) {
                                this.$emit('processing', 'shipping');
                            }

                            if (error.response?.data?.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },

                tryAutoSelectSingleRate() {
                    if (! this.methods) {
                        return;
                    }

                    const rates = this.flattenRates(this.methods);

                    if (rates.length !== 1) {
                        return;
                    }

                    const [singleRate] = rates;

                    if (this.selectedRate === singleRate.method) {
                        return;
                    }

                    this.selectedRate = singleRate.method;

                    const skipStepChange = this.currentStep !== 'shipping';

                    this.store(singleRate.method, {
                        skipStepChange,
                    });
                },

                flattenRates(methods) {
                    if (! methods) {
                        return [];
                    }

                    return Object.values(methods).reduce((accumulator, method) => {
                        const rates = method?.rates ?? [];

                        rates.forEach(rate => accumulator.push(rate));

                        return accumulator;
                    }, []);
                },
            },
        });
    </script>
@endPushOnce
