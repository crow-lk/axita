<div class="w-full rounded-2xl border border-zinc-200 bg-white p-4 text-[13px] leading-5 shadow-sm max-sm:p-3">
    <!-- Header -->
    <h1 class="text-base font-semibold text-navyBlue">
        @lang('shop::app.checkout.onepage.summary.cart-summary')
    </h1>

    <div class="mt-4 space-y-3.5">
        <!-- Cart Items -->
        <div class="space-y-3 border-b border-zinc-200 pb-3">
            <div
                class="flex gap-3 last:pb-0"
                v-for="item in cart.items"
            >
                {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.before') !!}

                <img
                    class="h-16 w-16 flex-shrink-0 rounded-lg object-cover"
                    :src="item.base_image.small_image_url"
                    :alt="item.name"
                    width="70"
                    height="70"
                />

                {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.after') !!}

                <div class="flex-1">
                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.before') !!}

                    <p class="font-medium text-navyBlue">
                        @{{ item.name }}
                    </p>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

                    <p class="mt-1 text-xs text-zinc-600">
                        <template v-if="displayTax.prices == 'including_tax'">
                            @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price_incl_tax }}', 'qty' => '@{{ item.quantity }}'])
                        </template>

                        <template v-else-if="displayTax.prices == 'both'">
                            @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price_incl_tax }}', 'qty' => '@{{ item.quantity }}'])

                            <span class="block text-[11px] text-zinc-500">
                                @lang('shop::app.checkout.onepage.summary.excl-tax')

                                <span class="font-semibold text-zinc-600">@{{ item.formatted_total }}</span>
                            </span>
                        </template>

                        <template v-else>
                            @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price }}', 'qty' => '@{{ item.quantity }}'])
                        </template>
                    </p>
                </div>
            </div>
        </div>

        <!-- Cart Totals -->
        <div class="space-y-2.5">
            <!-- Sub Total -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

            <template v-if="displayTax.subtotal == 'including_tax'">
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.sub-total')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_sub_total_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else-if="displayTax.subtotal == 'both'">
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.sub-total-excl-tax')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_sub_total }}
                    </p>
                </div>
                
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.sub-total-incl-tax')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_sub_total_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else>
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.sub-total')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_sub_total }}
                    </p>
                </div>
            </template>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}

            <!-- Discount -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.before') !!}

            <div
                class="flex justify-between text-right"
                v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0"
            >
                <p class="text-zinc-500">
                    @lang('shop::app.checkout.onepage.summary.discount-amount')
                </p>

                <p class="font-semibold text-navyBlue">
                    @{{ cart.formatted_discount_amount }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.after') !!}

            <!-- Apply Coupon -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

            @include('shop::checkout.coupon')

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

            <!-- Shipping Rates -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}
                
            <template v-if="displayTax.shipping == 'including_tax'">
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.delivery-charges')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else-if="displayTax.shipping == 'both'">
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.delivery-charges-excl-tax')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_shipping_amount }}
                    </p>
                </div>
                
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.delivery-charges-incl-tax')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else>
                <div class="flex justify-between text-right">
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.delivery-charges')
                    </p>

                    <p class="font-semibold text-navyBlue">
                        @{{ cart.formatted_shipping_amount }}
                    </p>
                </div>
            </template>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

            <!-- Payment Gateway Charges -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.payment_charges.before') !!}
            
            <div
                class="flex justify-between text-right"
                v-if="paymentMethodCharge > 0"
            >
                <p class="text-zinc-500">
                    Convenience Fee (@{{ cart.payment_method_title }})
                </p>

                <p class="font-semibold text-navyBlue">
                    @{{ formattedPaymentMethodCharge }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.payment_charges.after') !!}

            <!-- Taxes -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.before') !!}

            <div
                class="flex justify-between text-right"
                v-if="! cart.tax_total"
            >
                <p class="text-zinc-500">
                    @lang('shop::app.checkout.onepage.summary.tax')
                </p>

                <p class="font-semibold text-navyBlue">
                    @{{ cart.formatted_tax_total }}
                </p>
            </div>

            <div
                class="flex flex-col gap-1.5 border-y border-zinc-200 py-2"
                v-else
            >
                <div
                    class="flex cursor-pointer justify-between text-right"
                    @click="cart.show_taxes = ! cart.show_taxes"
                >
                    <p class="text-zinc-500">
                        @lang('shop::app.checkout.onepage.summary.tax')
                    </p>

                    <p class="flex items-center gap-1 font-semibold text-navyBlue">
                        @{{ cart.formatted_tax_total }}
                        
                        <span
                            class="text-lg"
                            :class="{'icon-arrow-up': cart.show_taxes, 'icon-arrow-down': ! cart.show_taxes}"
                        ></span>
                    </p>
                </div>

                <div
                    class="flex flex-col gap-1 text-xs text-zinc-500"
                    v-show="cart.show_taxes"
                >
                    <div
                        class="flex justify-between text-right"
                        v-for="(amount, index) in cart.applied_taxes"
                    >
                        <p>@{{ index }}</p>

                        <p class="font-semibold text-navyBlue">
                            @{{ amount }}
                        </p>
                    </div>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.after') !!}
        </div>

        <!-- Cart Grand Total -->
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

        <div class="flex justify-between text-right">
            <p class="text-sm font-semibold text-navyBlue">
                @lang('shop::app.checkout.onepage.summary.grand-total')
            </p>

            <p class="text-sm font-semibold text-navyBlue">
                @{{ formattedGrandTotalWithPayment }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}
    </div>
</div>
