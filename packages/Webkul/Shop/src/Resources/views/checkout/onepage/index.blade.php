<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.onepage.index.checkout')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.before') !!}

    <!-- Page Header -->
    <div class="flex-wrap">
        <div class="flex w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] py-4 max-lg:px-8 max-sm:px-4">
            <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="flex min-h-[30px]"
                    aria-label="@lang('shop::checkout.onepage.index.bagisto')"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="131"
                        height="29"
                    >
                </a>
            </div>

            @guest('customer')
                @include('shop::checkout.login')
            @endguest
        </div>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.after') !!}

    <!-- Page Content -->
    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">

        {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.before') !!}

        <!-- Breadcrumbs -->
        @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
            <x-shop::breadcrumbs name="checkout" />
        @endif

        {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.after') !!}

        <!-- Checkout Vue Component -->
        <v-checkout>
            <!-- Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage />
        </v-checkout>
    </div>

    @pushOnce('styles')
        <style>
            .checkout-onepage .text-navyBlue,
            .checkout-onepage .text-navyBlue *,
            .checkout-onepage h1,
            .checkout-onepage h2,
            .checkout-onepage h3,
            .checkout-onepage h4 {
                color: #1f2937 !important;
            }

            .checkout-onepage .text-blue-700,
            .checkout-onepage .text-blue-600,
            .checkout-onepage .text-orange-600,
            .checkout-onepage .text-orange-500,
            .checkout-onepage .text-orange-900 {
                color: #3f3f46 !important;
            }

            .checkout-onepage .border-navyBlue {
                border-color: #d4d4d8 !important;
            }

            .checkout-onepage .bg-navyBlue {
                background-color: #1f2937 !important;
                border-color: #1f2937 !important;
            }

            .checkout-onepage .bg-navyBlue\/\[0\.08\] {
                background-color: rgba(31, 41, 55, 0.08) !important;
            }

            .checkout-onepage .border-navyBlue\/30 {
                border-color: rgba(156, 163, 175, 0.4) !important;
            }

            .checkout-onepage .hover\:border-navyBlue\/30:hover {
                border-color: rgba(156, 163, 175, 0.6) !important;
            }

            .checkout-onepage .hover\:bg-\[\#050e3a0d\]:hover {
                background-color: #f4f4f5 !important;
            }
        </style>
    @endPushOnce

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-checkout-template"
        >
            <template v-if="! cart">
                <!-- Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage />
            </template>

            <template v-else>
                <div class="checkout-onepage grid text-[13px] leading-6 text-zinc-700 lg:grid-cols-[minmax(0,1fr)_minmax(360px,1fr)] gap-5 max-lg:grid-cols-1 max-md:gap-3.5">
                    <div
                        class="flex flex-col gap-5"
                        id="steps-container"
                    >
                        <!-- Included Addresses Blade File -->
                        <template v-if="['address', 'shipping', 'payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.address')
                        </template>
                    </div>

                    <div class="flex flex-col gap-4 lg:gap-5">
                        <div class="space-y-2.5">
                            <div id="summary-section">
                                @include('shop::checkout.onepage.summary')
                            </div>
                        </div>

                        <!-- Included Shipping Methods Blade File -->
                        <template v-if="cart.have_stockable_items && (['shipping', 'payment', 'review'].includes(currentStep) || currentStep === 'address' || shippingMethods !== null)">
                            <div id="shipping-section">
                                @include('shop::checkout.onepage.shipping')
                            </div>
                        </template>

                        <!-- Included Payment Methods Blade File -->
                        <template v-if="['payment', 'review'].includes(currentStep) || paymentMethods !== null">
                            <div id="payment-section" class="space-y-3">
                                @include('shop::checkout.onepage.payment')

                                <div
                                    class="flex justify-end"
                                    v-if="canPlaceOrder"
                                >
                                    <template v-if="cart.payment_method == 'paypal_smart_button'">
                                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.before') !!}

                                        <!-- Paypal Smart Button Vue Component -->
                                        <v-paypal-smart-button></v-paypal-smart-button>

                                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.after') !!}
                                    </template>

                                    <template v-else>
                                        <x-shop::button
                                            type="button"
                                            class="primary-button w-max rounded-2xl bg-navyBlue px-11 py-3 max-md:mb-4 max-md:w-full max-md:max-w-full max-md:rounded-lg max-sm:py-1.5"
                                            :title="trans('shop::app.checkout.onepage.summary.place-order')"
                                            ::disabled="isPlacingOrder"
                                            ::loading="isPlacingOrder"
                                            @click="placeOrder"
                                        />
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: null,

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",

                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                            
                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isPlacingOrder: false,

                        currentStep: 'address',

                        shippingMethods: null,

                        paymentMethods: null,

                        canPlaceOrder: false,
                    }
                },

                mounted() {
                    this.getCart().catch(() => {});
                },

                computed: {
                    /**
                     * Convenience fee returned by backend.
                     */
                    paymentMethodCharge() {
                        const rawCharge = parseFloat(this.cart?.payment_method_charge ?? 0);

                        return Number.isFinite(rawCharge) ? rawCharge : 0;
                    },

                    /**
                     * Pre-formatted convenience fee.
                     */
                    formattedPaymentMethodCharge() {
                        if (this.cart?.formatted_payment_method_charge) {
                            return this.cart.formatted_payment_method_charge;
                        }

                        return this.formatCurrency(this.paymentMethodCharge);
                    },

                    /**
                     * Grand total already includes the convenience fee.
                     */
                    grandTotalWithPayment() {
                        const grandTotal = parseFloat(this.cart?.grand_total ?? 0);

                        return Number.isFinite(grandTotal) ? grandTotal : 0;
                    },

                    /**
                     * Pre-formatted grand total with charge.
                     */
                    formattedGrandTotalWithPayment() {
                        if (this.cart?.formatted_grand_total) {
                            return this.cart.formatted_grand_total;
                        }

                        return this.formatCurrency(this.grandTotalWithPayment);
                    }
                },

                methods: {
                    formatCurrency(amount) {
                        const value = parseFloat(amount ?? 0);

                        if (! Number.isFinite(value)) {
                            return 'Rs. 0.00';
                        }

                        return `Rs. ${value.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    },

                    getCart() {
                        return this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                const payload = response.data ?? {};

                                this.cart = payload.data ?? null;

                                if (Object.prototype.hasOwnProperty.call(payload, 'shipping_methods')) {
                                    this.shippingMethods = payload.shipping_methods
                                        ? payload.shipping_methods.shippingMethods
                                        : null;
                                }

                                if (Object.prototype.hasOwnProperty.call(payload, 'payment_methods')) {
                                    this.paymentMethods = payload.payment_methods ?? [];
                                }

                                this.scrollToCurrentStep();
                            })
                            .catch(error => {
                                console.error('Failed to fetch cart summary.', error);

                                throw error;
                            });
                    },

                    stepForward(step) {
                        this.currentStep = step;

                        if (step == 'review') {
                            this.canPlaceOrder = true;

                            return;
                        }

                        this.canPlaceOrder = false;

                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = null;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = null;
                        }
                    },

                    stepProcessed(data) {
                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = data;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = data;
                        }

                        this.getCart().catch(() => {});
                    },

                    handleAutoSelectedShipping(paymentMethods) {
                        this.paymentMethods = paymentMethods ?? [];

                        this.getCart().catch(() => {});
                    },

                    scrollToCurrentStep() {
                        let targetId = 'steps-container';

                        if (this.currentStep === 'shipping') {
                            targetId = 'shipping-section';
                        } else if (this.currentStep === 'payment') {
                            targetId = 'payment-section';
                        } else if (this.currentStep === 'review') {
                            targetId = 'summary-section';
                        }

                        let container = document.getElementById(targetId);

                        if (! container) {
                            return;
                        }

                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    },

                    async placeOrder() {
                        this.isPlacingOrder = true;

                        try {
                            await this.prepareCheckoutData();

                            console.log('=== PAYZY CHECKOUT: Place Order Started ===');
                            console.log('Current Step:', this.currentStep);
                            console.log('Selected Payment Method:', this.cart?.payment?.method);
                            console.log('Cart Data:', this.cart);
                            console.log('API Endpoint:', '{{ route('shop.checkout.onepage.orders.store') }}');

                            const response = await this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}');

                            console.log('=== PAYZY CHECKOUT: Order Response Received ===');
                            console.log('Response Status:', response.status);
                            console.log('Response Data:', response.data);
                            
                            if (response.data.data.redirect) {
                                console.log('=== PAYZY CHECKOUT: Redirect Required ===');
                                console.log('Redirect URL:', response.data.data.redirect_url);
                                console.log('Payment Method:', response.data.data.method || 'Unknown');
                                
                                // Log before redirect
                                console.log('Redirecting to PayZY payment gateway...');
                                
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                console.log('=== PAYZY CHECKOUT: No Redirect, Going to Success Page ===');
                                window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                            }
                        } catch (error) {
                            console.error('=== PAYZY CHECKOUT: Order Error ===');
                            console.error('Error Status:', error?.response?.status);
                            console.error('Error Message:', error?.response?.data?.message || error?.message);
                            console.error('Error Data:', error?.response?.data);
                            console.error('Full Error:', error);

                            const message = error?.response?.data?.message
                                ?? error?.message
                                ?? 'Unable to place the order. Please review the form and try again.';

                            this.$emitter.emit('add-flash', { type: 'error', message });
                        } finally {
                            this.isPlacingOrder = false;
                        }
                    },

                    async prepareCheckoutData() {
                        if (! this.cart) {
                            await this.getCart();
                        }

                        let shippingMethods = null;

                        if (this.cart?.is_guest) {
                            shippingMethods = await this.ensureGuestAddresses();
                        }

                        await this.ensureDefaultShippingMethod(shippingMethods);
                    },

                    async ensureGuestAddresses() {
                        const guestComponent = this.$refs.guestAddressComponent;

                        if (! guestComponent || typeof guestComponent.submitAddressForm !== 'function') {
                            return null;
                        }

                        const result = await guestComponent.submitAddressForm({ silent: true });

                        await this.getCart();

                        return result?.shippingMethods ?? null;
                    },

                    async ensureDefaultShippingMethod(prefetchedMethods = null) {
                        if (! this.cart?.have_stockable_items) {
                            return;
                        }

                        if (this.cart.shipping_method) {
                            return;
                        }

                        let availableMethods = prefetchedMethods;

                        if (
                            ! availableMethods
                            || ! Object.keys(availableMethods).length
                        ) {
                            if (
                                ! this.shippingMethods
                                || ! Object.keys(this.shippingMethods).length
                            ) {
                                await this.getCart();
                            }

                            availableMethods = this.shippingMethods;
                        }

                        const defaultRate = this.pickFirstShippingRate(availableMethods);

                        if (! defaultRate?.method) {
                            throw new Error('Shipping method could not be determined. Please double-check the address information.');
                        }

                        await this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                            shipping_method: defaultRate.method,
                        });

                        await this.getCart();
                    },

                    pickFirstShippingRate(methodGroups) {
                        if (! methodGroups) {
                            return null;
                        }

                        const groups = Array.isArray(methodGroups)
                            ? methodGroups
                            : Object.values(methodGroups);

                        for (const group of groups) {
                            const rates = group?.rates ?? [];

                            if (rates.length) {
                                return rates[0];
                            }
                        }

                        return null;
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
