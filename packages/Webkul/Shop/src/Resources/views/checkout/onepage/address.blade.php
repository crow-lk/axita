{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<div class="mb-7 mt-8 max-md:mb-0 max-md:mt-0">
    <h2 class="mb-4 text-2xl font-medium max-md:text-base">
        @lang('shop::app.checkout.onepage.address.title')
    </h2>

    <!-- If the customer is guest -->
    <template v-if="cart.is_guest">
        @include('shop::checkout.onepage.address.guest')
    </template>

    <!-- If the customer is logged in -->
    <template v-else>
        @include('shop::checkout.onepage.address.customer')
    </template>
</div>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}