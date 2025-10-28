{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.before') !!}

<v-payment-methods
    :methods="paymentMethods"
    :cart="cart"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-payment-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.after') !!}

@pushOnce('styles')
    <style>
        .payment-detail-enter-active,
        .payment-detail-leave-active {
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .payment-detail-enter-from,
        .payment-detail-leave-to {
            opacity: 0;
            transform: translateY(-6px);
            max-height: 0;
        }

        .payment-detail-enter-to,
        .payment-detail-leave-from {
            opacity: 1;
            transform: translateY(0);
            max-height: 320px;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-payment-methods-template"
    >
        <div class="mb-1 max-md:last:!mb-0">
            <template v-if="! methods">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>
    
            <template v-else>
                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.before') !!}

                <!-- Accordion Blade Component -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="px-0 py-3 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">
                        
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-navyBlue max-md:text-base">
                                @lang('shop::app.checkout.onepage.payment.payment-method')
                            </h2>
                        </div>
                    </x-slot>
    
                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-4 !p-0 text-[13px] leading-5 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <div class="flex w-full flex-col gap-3 max-sm:gap-2.5">
                            <div 
                                class="relative w-full cursor-pointer"
                                v-for="(payment, index) in methods"
                            >
                                {!! view_render_event('bagisto.shop.checkout.payment-method.before') !!}

                                <input 
                                    type="radio" 
                                    name="payment[method]" 
                                    :value="payment.payment"
                                    :id="payment.method"
                                    class="peer hidden"
                                    :checked="selectedPayment === payment.method"
                                    @change="store(payment)"
                                >
    
                                <label 
                                    :for="payment.method" 
                                    :class="[
                                        'flex w-full cursor-pointer items-start gap-3 rounded-lg border px-3 py-2 text-[13px] transition max-md:flex-row max-md:items-center',
                                        selectedPayment === payment.method
                                            ? 'border-navyBlue bg-navyBlue/[0.08] text-navyBlue shadow-sm'
                                            : 'border-transparent text-zinc-600 hover:border-navyBlue/30 hover:bg-zinc-50'
                                    ]"
                                >
                                    <span
                                        class="mt-1 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full border transition max-md:mt-0"
                                        :class="selectedPayment === payment.method ? 'border-navyBlue bg-navyBlue/10' : 'border-zinc-300 bg-transparent'"
                                    >
                                        <span
                                            class="h-2 w-2 rounded-full transition"
                                            :class="selectedPayment === payment.method ? 'bg-navyBlue' : 'bg-transparent'"
                                        ></span>
                                    </span>

                                    <div class="flex w-full items-start justify-between gap-4">
                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.before') !!}

                                        <div class="flex items-start gap-3">
                                            <div class="flex h-10 w-12 flex-shrink-0 items-center justify-center rounded-md bg-white/60">
                                                <img
                                                    v-if="payment.image"
                                                    :src="payment.image"
                                                    :alt="payment.method_title"
                                                    :title="payment.method_title"
                                                    class="max-h-8 max-w-full object-contain"
                                                />
                                                <span v-else class="text-[11px] font-semibold uppercase text-navyBlue">
                                                    @{{ payment.method_code ? payment.method_code : payment.method }}
                                                </span>
                                            </div>

                                            <div class="flex flex-col">
                                                <span class="font-semibold text-navyBlue">
                                                    @{{ payment.method_title }}
                                                </span>
                                                
                                                {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.after') !!}

                                                {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.before') !!}

                                                <transition name="payment-detail">
                                                    <div
                                                        v-if="selectedPayment === payment.method"
                                                        class="mt-1 flex flex-col gap-1"
                                                    >
                                                        <span class="text-[11px] text-zinc-500">
                                                            @{{ payment.description }}
                                                        </span>

                                                        <!-- Payment arrangement description -->
                                                        <span v-if="payment.method === 'payzy'" class="text-[11px] text-zinc-400">
                                                            You will be redirected to Payzy to complete payment in 4 installments
                                                        </span>
                                                        <span v-else-if="payment.method === 'koko'" class="text-[11px] text-zinc-400">
                                                            You will be redirected to KOKO to complete payment in 3 installments
                                                        </span>
                                                        <span v-else-if="payment.method === 'paypal_standard'" class="text-[11px] text-zinc-400">
                                                            You will be redirected to PayHere to complete secure online payment
                                                        </span>
                                                        <span v-else-if="payment.method === 'paypal_smart_button'" class="text-[11px] text-zinc-400">
                                                            Pay securely using your PayPal account
                                                        </span>
                                                        <span v-else-if="payment.method === 'cashondelivery'" class="text-[11px] text-zinc-400">
                                                            Pay cash to the delivery person upon receiving your order
                                                        </span>
                                                        <span v-else-if="payment.method === 'moneytransfer'" class="text-[11px] text-zinc-400">
                                                            Transfer the amount to our bank account and upload the receipt
                                                        </span>

                                                        <span
                                                            v-if="payment.method === 'payzy'"
                                                            class="text-[11px] font-semibold text-navyBlue"
                                                        >
                                                            Pay @{{ formattedPayzyInstallment }} x 4 installments
                                                        </span>
                                                        <span
                                                            v-else-if="payment.method === 'koko'"
                                                            class="text-[11px] font-semibold text-navyBlue"
                                                        >
                                                            Pay @{{ formattedKokoInstallment }} x 3 installments
                                                        </span>
                                                    </div>
                                                </transition>

                                                {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.after') !!}
                                            </div>
                                        </div>

                                        <transition name="payment-detail">
                                            <div
                                                v-if="selectedPayment === payment.method"
                                                class="flex flex-col items-end justify-between gap-1"
                                            >
                                                <span
                                                    class="text-sm font-semibold text-navyBlue"
                                                    v-if="payment.additional_amount_label"
                                                    v-html="payment.additional_amount_label"
                                                ></span>

                                                <span
                                                    class="text-xs font-medium text-zinc-500"
                                                    v-if="payment.additional_fee"
                                                >
                                                    @{{ payment.additional_fee }}
                                                </span>
                                            </div>
                                        </transition>
                                    </div>
                                </label>

                                {!! view_render_event('bagisto.shop.checkout.payment-method.after') !!}

                                <!-- Todo implement the additionalDetails -->
                                {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                            </div>
                        </div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.after') !!}
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-methods', {
            template: '#v-payment-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
                
                cart: {
                    type: Object,
                    default: () => null,
                },
            },

            emits: ['processing', 'processed'],

            data() {
                return {
                    selectedPayment: this.cart && this.cart.payment ? this.cart.payment.method : null,
                };
            },

            watch: {
                cart: {
                    handler(newCart) {
                        if (newCart && newCart.payment && newCart.payment.method) {
                            this.selectedPayment = newCart.payment.method;
                        }
                    },
                    deep: true,
                },
            },

            computed: {
                /**
                 * Calculate the Payzy installment amount including convenience fee.
                 */
                payzyInstallmentAmount() {
                    if (! this.cart) {
                        return 0;
                    }

                    const grandTotal = parseFloat(this.cart.grand_total ?? 0);

                    if (! Number.isFinite(grandTotal) || grandTotal <= 0) {
                        return 0;
                    }

                    return grandTotal / 4;
                },

                /**
                 * Format Payzy installment as currency.
                 */
                formattedPayzyInstallment() {
                    const amount = this.payzyInstallmentAmount;

                    return `Rs. ${amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                },

                /**
                 * Calculate the KOKO installment amount including convenience fee.
                 */
                kokoInstallmentAmount() {
                    if (! this.cart) {
                        return 0;
                    }

                    const grandTotal = parseFloat(this.cart.grand_total ?? 0);

                    if (! Number.isFinite(grandTotal) || grandTotal <= 0) {
                        return 0;
                    }

                    return grandTotal / 3;
                },

                /**
                 * Format KOKO installment as currency.
                 */
                formattedKokoInstallment() {
                    const amount = this.kokoInstallmentAmount;

                    return `Rs. ${amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                },
            },

            methods: {
                store(selectedMethod) {
                    this.selectedPayment = selectedMethod.method;

                    this.$emit('processing', 'review');

                    this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                            payment: selectedMethod
                        })
                        .then(response => {
                            this.$emit('processed', response.data.cart);

                            // Used in mobile view. 
                            if (window.innerWidth <= 768) {
                                window.scrollTo({
                                    top: document.body.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'payment');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
