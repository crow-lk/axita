<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')" />

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')" />
@endPush

<x-shop::layouts
                 :has-header="false"
                 :has-feature="false"
                 :has-footer="false">
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
                   aria-label="@lang('shop::checkout.onepage.index.bagisto')">
                    <img
                         src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                         alt="{{ config('app.name') }}"
                         width="131"
                         height="29">
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
        @if (core()->getConfigData('general.general.breadcrumbs.shop'))
            <x-shop::breadcrumbs name="checkout" />
        @endif

        {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.after') !!}

        <!-- Checkout Vue Component -->
        <v-checkout>
            <!-- Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage />
        </v-checkout>
    </div>

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
                <div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr] max-md:gap-5">
                    <!-- Included Checkout Summary Blade File For Mobile view -->
                    <div class="hidden max-md:block">
                        @include('shop::checkout.onepage.summary')
                    </div>

                    <div
                        class="overflow-y-auto max-md:grid max-md:gap-4"
                        id="steps-container"
                    >
                        <!-- Included Addresses Blade File -->
                        @include('shop::checkout.onepage.address')

                        <!-- Included Shipping Methods Blade File -->
                        <template v-if="cart.have_stockable_items">
                            @include('shop::checkout.onepage.shipping')
                        </template>

                        <!-- Included Payment Methods Blade File -->
                        @include('shop::checkout.onepage.payment')
                    </div>

                    <!-- Included Checkout Summary Blade File For Desktop view -->
                    <div class="sticky top-8 block h-max w-[442px] max-w-full max-lg:w-auto max-lg:max-w-[442px] ltr:pl-8 max-lg:ltr:pl-0 rtl:pr-8 max-lg:rtl:pr-0">
                        <div class="block max-md:hidden">
                            @include('shop::checkout.onepage.summary')
                        </div>

                        <div class="flex justify-end">
                            <template v-if="cart.payment_method == 'paypal_smart_button' && canPlaceOrder">
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
                                    @click="handlePlaceOrder"
                                />
                            </template>
                        </div>
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

                        pendingShippingMethod: null,
                    }
                },

                computed: {
                    canPlaceOrder() {
                        if (!this.cart) {
                            return false;
                        }

                        // Check if billing address exists
                        if (!this.cart.billing_address) {
                            return false;
                        }

                        // Check if shipping method is selected (for stockable items)
                        if (this.cart.have_stockable_items && !this.cart.shipping_method) {
                            return false;
                        }

                        // Check if payment method is selected
                        if (!this.cart.payment_method) {
                            return false;
                        }

                        return true;
                    }
                },
                mounted() {
                    this.getCart();
                },

                methods: {
                    getCart() {
                        return this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {});
                    },

                    stepForward(section) {
                        // No longer needed for simplified checkout, but kept for compatibility
                    },

                    stepProcessed(data) {
                        // Refresh cart data to update shipping_rates and payment_methods
                        this.getCart().then(() => {
                            // If there's a pending shipping method, save it now
                            if (this.pendingShippingMethod && this.cart.billing_address) {
                                const shippingMethod = this.pendingShippingMethod;
                                this.pendingShippingMethod = null;

                                // Call the shipping component's store method
                                if (this.$refs.shippingComponent) {
                                    this.$refs.shippingComponent.store(shippingMethod);
                                }
                            }
                        });
                    },

                    handleSaveAddressFirst(shippingMethod) {
                        // Store the shipping method to be saved after address is saved
                        this.pendingShippingMethod = shippingMethod;

                        // For guest users, trigger address form submission
                        if (this.cart.is_guest && this.$refs.guestAddressComponent) {
                            this.$refs.guestAddressComponent.proceedGuest();
                        } else {
                            // For logged-in customers, show a message
                            this.$emitter.emit('add-flash', {
                                type: 'warning',
                                message: '@lang('shop::app.checkout.onepage.shipping.save-address-first')'
                            });
                        }
                    },

                    handlePlaceOrder() {
                        if (this.canPlaceOrder) {
                            // All conditions met, place the order
                            this.placeOrder();
                        } else {
                            // Trigger the proceed logic to save address/validate
                            // User will need to click again after address is saved
                            if (this.$refs.guestAddressComponent) {
                                this.$refs.guestAddressComponent.proceedGuest();
                            }
                        }
                    },

                    placeOrder() {
                        this.isPlacingOrder = true;

                        this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                            .then(response => {
                                if (response.data.data.redirect) {
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                                }

                                this.isPlacingOrder = false;
                            })
                            .catch(error => {
                                this.isPlacingOrder = false

                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response.data.message
                                });
                            });
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
