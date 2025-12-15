{!! view_render_event('bagisto.shop.checkout.onepage.address.guest.before') !!}

<!-- Guest Address Vue Component -->
<v-checkout-address-guest
    ref="guestAddressComponent"
    :cart="cart"
    @processing="stepForward"
    @processed="stepProcessed"
></v-checkout-address-guest>

{!! view_render_event('bagisto.shop.checkout.onepage.address.guest.after') !!}

@include('shop::checkout.onepage.address.form')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-guest-template"
    >
        <!-- Address Form -->
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form
                ref="guestAddressForm"
                @submit="handleSubmit($event, addAddress)"
            >
                <!-- Guest Billing Address -->
                <div class="mb-4">
                    {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.billing.before') !!}

                    <!-- Billing Address Header -->
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-navyBlue max-md:text-base">
                            @lang('shop::app.checkout.onepage.address.billing-address')
                        </h2>
                    </div>
                
                    <!-- Billing Address Form -->
                    <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm max-sm:p-3">
                        <v-checkout-address-form
                            control-name="billing"
                            :address="cart.billing_address || undefined"
                        ></v-checkout-address-form>

                        <!-- Use for Shipping Checkbox -->
                        <x-shop::form.control-group
                            class="mt-4 flex items-center gap-2.5 text-[13px]"
                            v-if="cart.have_stockable_items"
                        >
                            <x-shop::form.control-group.control
                                type="checkbox"
                                name="billing.use_for_shipping"
                                id="use_for_shipping"
                                for="use_for_shipping"
                                value="1"
                                @change="useBillingAddressForShipping = ! useBillingAddressForShipping"
                                ::checked="!! useBillingAddressForShipping"
                            />

                            <label
                                class="cursor-pointer select-none text-[13px] text-zinc-600 ltr:pl-0 rtl:pr-0"
                                for="use_for_shipping"
                            >
                                @lang('shop::app.checkout.onepage.address.same-as-billing')
                            </label>
                        </x-shop::form.control-group>

                        <!-- Proceed Button -->
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.billing.after') !!}
                </div>

                <!-- Guest Shipping Address -->
                <template v-if="cart.have_stockable_items">
                    <div
                        class="mt-8"
                        v-if="! useBillingAddressForShipping"
                    >
                        {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.shipping.before') !!}

                        <!-- Shipping Address Header -->
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-navyBlue max-md:text-base">
                                @lang('shop::app.checkout.onepage.address.shipping-address')
                            </h2>
                        </div>
                    
                        <!-- Shipping Address Form -->
                        <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm max-sm:p-3">
                            <v-checkout-address-form
                                control-name="shipping"
                                :address="cart.shipping_address || undefined"
                            ></v-checkout-address-form>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.shipping.after') !!}
                    </div>
                </template>

            </form>
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-checkout-address-guest', {
            template: '#v-checkout-address-guest-template',

            props: ['cart'],

            emits: ['processing', 'processed'],

            data() {
                return {
                    useBillingAddressForShipping: true,

                    isStoring: false,

                    silentSubmit: false,

                    pendingAddressPromise: null,
                }
            },

            created() {
                if (this.cart.billing_address) {
                    this.useBillingAddressForShipping = this.cart.billing_address.use_for_shipping;
                }
            },

            methods: {
                submitAddressForm(options = {}) {
                    const { silent = false } = options;

                    this.silentSubmit = silent;

                    return new Promise((resolve, reject) => {
                        this.pendingAddressPromise = { resolve, reject };

                        this.triggerAddressFormSubmission();
                    });
                },

                triggerAddressFormSubmission() {
                    const form = this.$refs.guestAddressForm;

                    if (! form) {
                        if (this.pendingAddressPromise) {
                            this.pendingAddressPromise.reject(new Error('Unable to locate the address form.'));

                            this.pendingAddressPromise = null;
                        }

                        this.silentSubmit = false;

                        return;
                    }

                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.dispatchEvent(new Event('submit', { cancelable: true }));
                    }
                },

                addAddress(params, { setErrors }) {
                    this.isStoring = true;

                    params['billing']['use_for_shipping'] = this.useBillingAddressForShipping;

                    if (! this.silentSubmit) {
                        this.moveToNextStep();
                    }

                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', params)
                        .then((response) => {
                            this.isStoring = false;

                            const payload = response?.data?.data ?? {};

                            if (payload.redirect_url) {
                                if (this.pendingAddressPromise) {
                                    this.pendingAddressPromise.resolve({
                                        shippingMethods: null,
                                        paymentMethods: null,
                                    });

                                    this.pendingAddressPromise = null;
                                }

                                this.silentSubmit = false;

                                window.location.href = payload.redirect_url;

                                return;
                            }

                            if (! this.silentSubmit) {
                                if (this.cart.have_stockable_items) {
                                    this.$emit('processed', payload.shippingMethods);
                                } else {
                                    this.$emit('processed', payload.payment_methods);
                                }
                            }

                            if (this.pendingAddressPromise) {
                                this.pendingAddressPromise.resolve({
                                    shippingMethods: payload.shippingMethods ?? null,
                                    paymentMethods: payload.payment_methods ?? null,
                                });

                                this.pendingAddressPromise = null;
                            }

                            this.silentSubmit = false;
                        })
                        .catch(error => {
                            this.isStoring = false;

                            if (error.response?.status == 422) {
                                setErrors(error.response.data.errors);
                            }

                            if (this.pendingAddressPromise) {
                                this.pendingAddressPromise.reject(error);

                                this.pendingAddressPromise = null;
                            }

                            this.silentSubmit = false;
                        });
                },

                moveToNextStep() {
                    if (this.cart.have_stockable_items) {
                        this.$emit('processing', 'shipping');
                    } else {
                        this.$emit('processing', 'payment');
                    }
                }
            }
        });
    </script>
@endPushOnce
