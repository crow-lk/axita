@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = $reviewHelper->getAverageRating($product);

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));

    $productPrices = $product->getTypeInstance()->getProductPrices();

    $finalPriceValue = (float) ($productPrices['final']['price'] ?? 0);

    $installmentShippingFee = 500;

    $baseInstallmentAmount = $finalPriceValue + $installmentShippingFee;

    $payzyInstallment = $baseInstallmentAmount > 0 ? ($baseInstallmentAmount * 1.14) / 4 : 0;

    $kokoInstallment = $baseInstallmentAmount > 0 ? ($baseInstallmentAmount * 1.12) / 3 : 0;

    $payzyImage = core()->getConfigData('sales.payment_methods.payzy.image');

    $payzyLogo = $payzyImage ? asset('storage/'.$payzyImage) : null;

    $kokoImage = core()->getConfigData('sales.payment_methods.koko.image');

    $kokoLogo = $kokoImage ? asset('storage/'.$kokoImage) : asset('storage/logos/kokologo.png');
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
        
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div class="flex justify-center px-7 max-lg:hidden">
            <x-shop::breadcrumbs
                name="product"
                :entity="$product"
            />
        </div>
    @endif

    <!-- Product Information Vue Component -->
    <v-product>
        <x-shop::shimmer.products.view />
    </v-product>

    <!-- Information Section -->
    <div class="1180:mt-20">
        <div class="max-1180:hidden">
            <x-shop::tabs
                position="center"
                ref="productTabs"
            >
                <!-- Description Tab -->
                {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                <x-shop::tabs.item
                    id="descritpion-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.description')"
                    :is-selected="true"
                >
                    <div class="container mt-[40px] max-1180:px-5">
                        <div class="rounded-3xl border border-zinc-100 bg-white/85 p-8 shadow-sm shadow-zinc-200/30 max-1180:p-6 max-sm:p-4">
                            <div class="space-y-5 text-lg leading-7 text-zinc-600 max-1180:text-base max-sm:text-sm">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                </x-shop::tabs.item>

                {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                <!-- Additional Information Tab -->
                @if(count($attributeData))
                    <x-shop::tabs.item
                        id="information-tab"
                        class="container mt-[60px] !p-0"
                        :title="trans('shop::app.products.view.additional-information')"
                        :is-selected="false"
                    >
                        <div class="container mt-[40px] max-1180:px-5">
                            <div class="rounded-3xl border border-zinc-100 bg-white/85 p-8 shadow-sm shadow-zinc-200/30 max-1180:p-6 max-sm:p-4">
                                <div class="grid gap-6 text-sm text-zinc-600 sm:grid-cols-2">
                                    @foreach ($customAttributeValues as $customAttributeValue)
                                        @if (! empty($customAttributeValue['value']))
                                            <div class="flex flex-col gap-1 rounded-2xl border border-zinc-100 bg-white/70 p-5 shadow-sm shadow-zinc-200/20">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-zinc-400">
                                                    {!! $customAttributeValue['label'] !!}
                                                </span>

                                                @if ($customAttributeValue['type'] == 'file')
                                                    <a
                                                        href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                        download="{{ $customAttributeValue['label'] }}"
                                                        class="flex items-center gap-2 text-sm font-medium text-navyBlue hover:underline"
                                                    >
                                                        <span class="icon-download text-lg"></span>
                                                        {{ __('Download') }}
                                                    </a>
                                                @elseif ($customAttributeValue['type'] == 'image')
                                                    <a
                                                        href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                        download="{{ $customAttributeValue['label'] }}"
                                                        class="flex items-center gap-2"
                                                    >
                                                        <img
                                                            class="h-12 w-12 rounded-xl border border-zinc-200 object-cover"
                                                            src="{{ Storage::url($customAttributeValue['value']) }}"
                                                            alt="{{ $customAttributeValue['label'] }}"
                                                        />
                                                        <span class="text-sm font-medium text-navyBlue hover:underline">
                                                            {{ __('View') }}
                                                        </span>
                                                    </a>
                                                @else
                                                    <p class="text-base font-medium text-zinc-700 max-sm:text-sm">
                                                        {!! $customAttributeValue['value'] !!}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </x-shop::tabs.item>
                @endif

                <!-- Reviews Tab -->
                <x-shop::tabs.item
                    id="review-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.review')"
                    :is-selected="false"
                >
                    <div class="container mt-[40px] max-1180:px-5">
                        <div class="rounded-3xl border border-zinc-100 bg-white/85 p-8 shadow-sm shadow-zinc-200/30 max-1180:p-6 max-sm:p-4">
                            @include('shop::products.view.reviews')
                        </div>
                    </div>
                </x-shop::tabs.item>
            </x-shop::tabs>
        </div>
    </div>

    <!-- Information Section -->
    <div class="container mt-6 grid gap-3 !p-0 max-1180:px-5 1180:hidden">
        <!-- Description Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="true"
        >
            <x-slot:header class="rounded-2xl border border-zinc-200 bg-white/85 px-5 py-4 shadow-sm shadow-zinc-200/30 max-md:!py-3 max-sm:!py-2">
                <p class="text-base font-semibold text-zinc-800 1180:hidden">
                    @lang('shop::app.products.view.description')
                </p>
            </x-slot>

            <x-slot:content class="!bg-transparent !p-0 !rounded-none max-sm:px-0">
                <div class="mt-3 rounded-3xl border border-zinc-100 bg-white/85 p-6 text-lg leading-7 text-zinc-600 shadow-sm shadow-zinc-200/30 max-1180:text-base max-md:p-5 max-sm:text-sm">
                    {!! $product->description !!}
                </div>
            </x-slot>
        </x-shop::accordion>

        <!-- Additional Information Accordion -->
        @if (count($attributeData))
            <x-shop::accordion
                class="max-md:border-none"
                :is-active="false"
            >
                <x-slot:header class="rounded-2xl border border-zinc-200 bg-white/85 px-5 py-4 shadow-sm shadow-zinc-200/30 max-md:!py-3 max-sm:!py-2">
                    <p class="text-base font-semibold text-zinc-800 1180:hidden">
                        @lang('shop::app.products.view.additional-information')
                    </p>
                </x-slot>

                <x-slot:content class="!bg-transparent !p-0 !rounded-none max-sm:px-0">
                    <div class="container max-1180:px-5">
                        <div class="mt-3 grid gap-4 text-sm text-zinc-600">
                            @foreach ($customAttributeValues as $customAttributeValue)
                                @if (! empty($customAttributeValue['value']))
                                    <div class="rounded-2xl border border-zinc-100 bg-white/85 p-5 shadow-sm shadow-zinc-200/25">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-zinc-400">
                                            {{ $customAttributeValue['label'] }}
                                        </span>

                                        @if ($customAttributeValue['type'] == 'file')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                                class="mt-2 flex items-center gap-2 text-sm font-medium text-navyBlue hover:underline"
                                            >
                                                <span class="icon-download text-lg"></span>
                                                {{ __('Download') }}
                                            </a>
                                        @elseif ($customAttributeValue['type'] == 'image')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                                class="mt-2 flex items-center gap-2"
                                            >
                                                <img
                                                    class="h-12 w-12 rounded-xl border border-zinc-200 object-cover"
                                                    src="{{ Storage::url($customAttributeValue['value']) }}"
                                                    alt="{{ $customAttributeValue['label'] }}"
                                                />
                                                <span class="text-sm font-medium text-navyBlue hover:underline">
                                                    {{ __('View') }}
                                                </span>
                                            </a>
                                        @else
                                            <p class="mt-2 text-base font-medium text-zinc-700">
                                                {{ $customAttributeValue['value'] ?? '-' }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-slot>
            </x-shop::accordion>
        @endif

        <!-- Reviews Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="false"
        >
            <x-slot:header
                class="rounded-2xl border border-zinc-200 bg-white/85 px-5 py-4 shadow-sm shadow-zinc-200/30 max-md:!py-3 max-sm:!py-2"
                id="review-accordian-button"
            >
                <p class="text-base font-semibold text-zinc-800">
                    @lang('shop::app.products.view.review')
                </p>
            </x-slot>

            <x-slot:content class="!bg-transparent !p-0 !rounded-none">
                <div class="mt-3 rounded-3xl border border-zinc-100 bg-white/85 p-6 shadow-sm shadow-zinc-200/30">
                    @include('shop::products.view.reviews')
                </div>
            </x-slot>
        </x-shop::accordion>
    </div>

    <!-- Featured Products -->
    <x-shop::products.carousel
        :title="trans('shop::app.products.view.related-product-title')"
        :src="route('shop.api.products.related.index', ['id' => $product->id])"
    />

    <!-- Upsell Products -->
    <x-shop::products.carousel
        :title="trans('shop::app.products.view.up-sell-title')"
        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
    />

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >

                    <div class="container px-[60px] max-1180:px-0">
                        <div class="flex mt-12 gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4">
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between md:gap-6">
                                    <h1 class="text-3xl font-semibold leading-tight text-zinc-900 break-all max-sm:text-2xl">
                                        {{ $product->name }}
                                    </h1>

                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                        <div
                                            class="flex max-h-[46px] min-h-[46px] min-w-[46px] cursor-pointer items-center justify-center rounded-full border bg-white text-2xl transition-all hover:opacity-[0.8] max-sm:max-h-7 max-sm:min-h-7 max-sm:min-w-7 max-sm:text-base"
                                            role="button"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif
                                </div>

                                {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!}

                                <!-- Rating -->
                                {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

                                @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                                    <!-- Scroll To Reviews Section and Activate Reviews Tab -->
                                    <div
                                        class="mt-1 w-max cursor-pointer max-sm:mt-1.5"
                                        role="button"
                                        tabindex="0"
                                        @click="scrollToReview"
                                    >
                                        <x-shop::products.ratings
                                            class="transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1"
                                            :average="$avgRatings"
                                            :total="$totalRatings"
                                            ::rating="true"
                                        />
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                <div class="mt-6 max-sm:mt-4">
                                    <div class="flex flex-wrap items-end gap-x-3 gap-y-2 text-3xl font-semibold text-zinc-900 max-sm:text-2xl [&_.final-price]:text-xl [&_.final-price]:font-medium [&_.final-price]:text-zinc-400 [&_.line-through]:text-zinc-400 [&_.line-through]:decoration-zinc-300">
                                        {!! $product->getTypeInstance()->getPriceHtml() !!}
                                    </div>
                                </div>

                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                    <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                        (@lang('shop::app.products.view.tax-inclusive'))
                                    </span>
                                @endif

                                @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                                    <div class="mt-2.5 grid gap-1.5">
                                        @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                            <p class="text-zinc-500 [&>*]:text-black">
                                                {!! $offer !!}
                                            </p>
                                        @endforeach
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                <!-- Stock Status (Low Stock removed) -->
                                <div class="flex items-center gap-2 mt-4">
                                    @if($product->preorder)
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-blue-500 rounded-full"></span>
                                            @lang('shop::app.components.products.card.pre-order')
                                        </span>
                                    @elseif($product->inventories->sum('qty') <= 0)
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-red-500 rounded-full"></span>
                                            @lang('shop::app.components.products.card.out-of-stock')
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                                            @lang('shop::app.components.products.card.in-stock')
                                        </span>
                                    @endif
                                </div>

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                <p class="mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm">
                                    {!! $product->short_description !!}
                                </p>

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.configurable')

                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')


                                <!-- Product Actions and Qunatity Box -->
                                <div class="mt-8 flex max-w-[470px] gap-4 max-sm:mt-4">

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            value="1"
                                            class="gap-x-4 rounded-xl px-7 py-4 max-md:py-3 max-sm:gap-x-5 max-sm:rounded-lg max-sm:px-4 max-sm:py-1.5"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                        <!-- Add To Cart Button -->
                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

                                        <x-shop::button
                                            type="submit"
                                            class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            ::class="getAddToCartButtonClass()"
                                            button-type="secondary-button"
                                            :loading="false"
                                            ::title="getAddToCartButtonTitle()"
                                            ::loading="isStoring.addToCart"
                                            ::disabled="isStoring.addToCart || isProductOutOfStock()"
                                            @click="is_buy_now=0;"
                                        />

                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
                                    @endif
                                </div>

                                <!-- Buy Now Button -->
                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                    {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                    @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                        <x-shop::button
                                            type="submit"
                                            class="primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5"
                                            ::class="getAddToCartButtonClass()"
                                            button-type="primary-button"
                                            ::title="getBuyNowButtonTitle()"
                                            ::loading="isStoring.buyNow"
                                            @click="is_buy_now=1;"
                                            ::disabled="isStoring.buyNow || isProductOutOfStock()"
                                        />
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}
                            @endif

                            @if ($baseInstallmentAmount > 0)
                                <div class="mt-6 max-w-[470px] space-y-3 rounded-2xl border border-zinc-100 bg-zinc-50/80 p-4 text-sm text-zinc-600 max-sm:mt-4 max-sm:space-y-2 max-sm:p-3">
                                    <div class="flex items-center justify-between gap-3 border-b border-zinc-200 pb-2">
                                        <div class="flex items-center gap-2">
                                            @if ($payzyLogo)
                                                <img
                                                    src="{{ $payzyLogo }}"
                                                    alt="Payzy"
                                                    class="h-6 w-auto max-w-[90px] object-contain max-sm:h-5"
                                                />
                                                <span class="text-xs font-medium uppercase tracking-wide text-zinc-500">4x</span>
                                            @else
                                                <span class="font-medium text-zinc-700">Payzy (4x)</span>
                                            @endif
                                        </div>

                                        <span class="font-semibold text-zinc-900">
                                            {{ core()->formatPrice($payzyInstallment) }} <span class="text-xs font-medium text-zinc-500">/ month</span>
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            @if ($kokoLogo)
                                                <img
                                                    src="{{ $kokoLogo }}"
                                                    alt="KOKO"
                                                    class="h-6 w-auto max-w-[90px] object-contain max-sm:h-5"
                                                />
                                                <span class="text-xs font-medium uppercase tracking-wide text-zinc-500">3x</span>
                                            @else
                                                <span class="font-medium text-zinc-700">KOKO (3x)</span>
                                            @endif
                                        </div>

                                        <span class="font-semibold text-zinc-900">
                                            {{ core()->formatPrice($kokoInstallment) }} <span class="text-xs font-medium text-zinc-500">/ month</span>
                                        </span>
                                    </div>

                                    <p class="pt-1 text-xs text-zinc-400 max-sm:text-[11px]">
                                        Includes estimated shipping & finance fees. Final amount may vary at checkout.
                                    </p>
                                </div>
                            @endif

                            {!! view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]) !!}

                            <!-- Share Buttons -->
                                <div class="flex mt-10 gap-9 max-md:mt-4 max-md:flex-wrap max-sm:justify-center max-sm:gap-3">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <div
                                        class="flex cursor-pointer items-center justify-center gap-2.5 max-sm:gap-1.5 max-sm:text-base"
                                        role="button"
                                        tabindex="0"
                                        @click="is_buy_now=0; addToCompare({{ $product->id }})"
                                    >
                                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                                            <span
                                                class="text-2xl icon-compare"
                                                role="presentation"
                                            ></span>

                                            @lang('shop::app.products.view.compare')
                                        @endif
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        is_buy_now: 0,

                        productQuantity: {{ $product->inventories->sum('qty') }},

                        isStoring: {
                            addToCart: false,

                            buyNow: false,
                        },
                    }
                },

                methods: {
                    isProductOutOfStock() {
                        return this.productQuantity <= 0;
                    },

                    getAddToCartButtonClass() {
                        if (this.isProductOutOfStock()) {
                            return 'opacity-50 cursor-not-allowed bg-gray-400 border-gray-400';
                        }
                        return '';
                    },

                    getAddToCartButtonTitle() {
                        if (this.isProductOutOfStock()) {
                            return '@lang('shop::app.components.products.card.out-of-stock')';
                        }
                        return '@lang('shop::app.products.view.add-to-cart')';
                    },

                    getBuyNowButtonTitle() {
                        if (this.isProductOutOfStock()) {
                            return '@lang('shop::app.components.products.card.out-of-stock')';
                        }
                        return '@lang('shop::app.products.view.buy-now')';
                    },

                    addToCart(params) {
                        if (this.isProductOutOfStock()) {
                            this.$emitter.emit('add-flash', { type: 'warning', message: 'Sorry, this product is currently out of stock.' });
                            return;
                        }

                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';

                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData);

                        this.ensureQuantity(formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    if (response.data.redirect) {
                                        window.location.href= response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;

                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

                            return;
                        }

                        /**
                         * This will handle for guests.
                         */
                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (! existingItems.includes(productId)) {
                                existingItems.push(productId);

                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;

                        let qty = {};

                        qty[id] = quantity;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isLoading = false;
                            }).catch(error => this.isLoading = false);
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },

                    scrollToReview() {
                        let accordianElement = document.querySelector('#review-accordian-button');

                        if (accordianElement) {
                            accordianElement.click();

                            accordianElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                        
                        let tabElement = document.querySelector('#review-tab-button');

                        if (tabElement) {
                            tabElement.click();

                            tabElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    },

                    ensureQuantity(formData) {
                        if (! formData.has('quantity')) {
                            formData.append('quantity', 1);
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
