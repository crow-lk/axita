<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
        
    >
        <!-- Grid Card -->
        <div
            class="group flex w-full max-w-[320px] flex-col rounded-xl border border-zinc-200 bg-white p-2.5 shadow-sm transition-all duration-300 1180:relative 1180:overflow-hidden 1180:transition-all 1180:duration-300 1180:hover:shadow-[0_5px_10px_rgba(0,0,0,0.1)] max-lg:max-w-[280px] max-md:max-w-[240px] max-sm:rounded-xl max-sm:border-zinc-100 max-sm:p-2 max-sm:shadow-[0_10px_28px_rgba(15,23,42,0.08)]"
            v-if="mode != 'list'"
        >
            <div class="relative w-full aspect-square overflow-hidden max-md:rounded-xl max-sm:rounded-[22px]">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <!-- Product Image -->
                <a
                    :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                    :aria-label="product.name + ' '"
                >
                    <x-shop::media.images.lazy
                        class="w-full h-full object-cover bg-zinc-100 transition-all duration-300 group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="320"
                        height="320"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}
                
                <!-- Product Ratings -->
                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.ratings.total"
                        ::rating="false"
                        v-if="product.ratings.total"
                    />
                @else
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.reviews.total"
                        ::rating="false"
                        v-if="product.reviews.total"
                    />
                @endif

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                <div class="action-items">
                    <!-- Product Stock Badge with Quantity Info (Low Stock removed) -->
                    <p
                        class="absolute top-1.5 inline-block rounded-[44px] px-3 py-1 text-base font-semibold text-white max-sm:rounded-l-none max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-sm ltr:left-1.5 max-sm:ltr:left-0 rtl:right-5 max-sm:rtl:right-0 origin-center"
                        :style="getStockBadgeStyle()"
                    >
                        <span v-text="getStockBadgeText()"></span>
                    </p>

                    <div class="absolute top-3 flex flex-col items-center gap-2 transition-all duration-300 opacity-0 group-hover:opacity-100 max-lg:opacity-100 ltr:right-3 rtl:left-3">
                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <button
                                type="button"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-white/90 text-lg text-zinc-500 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-zinc-300 hover:text-red-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                @click="addToWishlist()"
                            >
                                <span
                                    class="text-xl transition-transform duration-200"
                                    :class="product.is_wishlist ? 'icon-heart-fill text-red-500' : 'icon-heart text-zinc-500'"
                                ></span>
                            </button>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                            <button
                                type="button"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-200 bg-white/90 text-lg text-zinc-500 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-zinc-300 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-600/30"
                                aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                @click="addToCompare(product.id)"
                            >
                                <span class="icon-compare text-xl"></span>
                            </button>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                    </div>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="relative grid flex-1 content-start gap-1.5 bg-white px-2 pt-2 pb-2.5 max-sm:w-full max-sm:gap-1.5 max-sm:px-0 max-sm:pt-2 max-sm:pb-2.5 max-sm:bg-transparent">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p
                    class="text-left text-[15px] font-semibold text-zinc-900 break-words leading-6 max-h-12 max-md:mb-1.5 max-md:max-w-full max-md:whitespace-normal max-md:text-sm max-md:leading-5 max-md:max-h-10 max-sm:text-sm max-sm:leading-5 max-sm:max-h-9"
                    :title="product.name"
                    style="display: -webkit-box; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 2; line-clamp: 2;"
                >
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                <!-- Pricing -->
                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="flex flex-wrap items-baseline gap-2 text-base font-semibold text-zinc-900 max-sm:text-sm max-sm:leading-5"
                    v-html="displayPriceHtml"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Compact payment summary (hidden on md+) -->
                <div class="w-full flex flex-col gap-2 rounded-lg border border-zinc-100 bg-white/80 p-2 text-[11px] text-gray-700 max-md:flex max-md:text-[12px] max-md:p-2.5 max-md:gap-2 md:hidden">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <img
                                v-if="payzyLogo"
                                :src="payzyLogo"
                                alt="Payzy"
                                class="h-4 w-auto max-w-[48px] object-contain max-md:h-5 max-md:max-w-[40px]"
                            />
                            <span v-else class="font-medium truncate">Payzy (4x)</span>
                            <span v-if="payzyLogo" class="font-medium text-[10px] text-gray-400">(4x)</span>
                        </div>
                        <span class="font-semibold text-gray-800 whitespace-nowrap">@{{ formatPrice(getPayzyInstallment()) }}</span>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-zinc-100 pt-1 mt-1">
                        <div class="flex items-center gap-2 min-w-0">
                            <img
                                v-if="kokoLogo"
                                :src="kokoLogo"
                                alt="KOKO"
                                class="h-4 w-auto max-w-[48px] object-contain max-md:h-5 max-md:max-w-[40px]"
                            />
                            <span v-else class="font-medium truncate">KOKO (3x)</span>
                            <span v-if="kokoLogo" class="font-medium text-[10px] text-gray-400">(3x)</span>
                        </div>
                        <span class="font-semibold text-gray-800 whitespace-nowrap">@{{ formatPrice(getKokoInstallment()) }}</span>
                    </div>
                </div>

                <!-- Payment Method Pricing -->
                <div class="mt-1.5 space-y-1 text-xs text-gray-600 max-md:hidden">
                    <!-- Payzy with Logo -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-1">
                        <div class="flex items-center gap-1">
                            <img
                                v-if="payzyLogo"
                                :src="payzyLogo"
                                alt="Payzy"
                                class="h-5 w-auto max-w-[72px] object-contain max-sm:h-5"
                            />
                            <span v-else class="font-medium">Payzy (4x):</span>
                            <span v-if="payzyLogo" class="font-medium text-[11px] max-sm:text-[9px]">(4x):</span>
                        </div>
                        <span class="font-semibold text-gray-800">@{{ formatPrice(getPayzyInstallment()) }} / month</span>
                    </div>
                    
                    <!-- KOKO with Logo -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            <img
                                v-if="kokoLogo"
                                :src="kokoLogo"
                                alt="KOKO"
                                class="h-5 w-auto max-w-[72px] object-contain max-sm:h-5"
                            />
                            <span v-else class="font-medium">KOKO (3x):</span>
                            <span v-if="kokoLogo" class="font-medium text-[11px] max-sm:text-[9px]">(3x):</span>
                        </div>
                        <span class="font-semibold text-gray-800">@{{ formatPrice(getKokoInstallment()) }} / month</span>
                    </div>
                </div>
            </div>

            <!-- Product Actions Section - Positioned Absolutely at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 flex flex-col gap-1.5 p-2.5 pt-5 bg-gradient-to-t from-white via-white to-transparent transition-all duration-300 ease-out transform translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 max-md:relative max-md:translate-y-0 max-md:opacity-100 max-md:bg-none max-md:pt-1.5 max-sm:gap-1.5">
                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <!-- Buy Now Button -->
                    <button
                        class="primary-button w-full max-w-full p-2 text-sm font-medium max-sm:rounded-xl max-sm:p-1.5"
                        :class="getAddToCartButtonClass()"
                        :disabled="isProductOutOfStock() || isAddingToCart"
                        @click="buyNow()"
                    >
                        <span>Buy Now</span>
                    </button>

                    <!-- Add to Cart Button -->
                    <button
                        class="secondary-button w-full max-w-full p-2 text-sm font-medium max-sm:rounded-xl max-sm:p-1.5"
                        :class="getAddToCartButtonClass()"
                        :disabled="isProductOutOfStock() || isAddingToCart"
                        @click="addToCart()"
                    >
                        <span v-text="getAddToCartButtonText()"></span>
                    </button>

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}
                @endif

                <!-- Compare and Wishlist Icons Row -->
                <div class="flex items-center justify-between">
                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                    <span class="hidden"></span>

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                    <span class="hidden"></span>

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div
            class="relative flex grid-cols-2 gap-4 overflow-hidden rounded border border-gray-200 p-2 max-w-max max-sm:flex-wrap"
            v-else
        >
            <div class="group relative max-h-[258px] max-w-[250px] overflow-hidden"> 

                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                    <x-shop::media.images.lazy
                        class="after:content-[' '] relative min-w-[250px] bg-zinc-100 transition-all duration-300 after:block after:pb-[calc(100%+9px)] group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <div class="action-items">
                    <!-- Product Stock Badge Only -->
                    <p
                        class="absolute top-5 inline-block rounded-[44px] px-3 py-1 text-base font-semibold text-white ltr:left-5 max-sm:ltr:left-2 rtl:right-5"
                        :style="product.quantity !== undefined ? (product.quantity <= 0 ? 'background-color:#dc2626' : 'background-color:#16a34a') : ((product.is_saleable !== undefined ? product.is_saleable : true) ? 'background-color:#16a34a' : 'background-color:#dc2626')"
                    >
                        <span v-if="product.quantity !== undefined">
                            <span v-if="product.quantity <= 0">Out of Stock</span>
                            <span v-else>In Stock</span>
                        </span>
                        <span v-else>
                            <span v-if="product.is_saleable !== undefined ? product.is_saleable : true">In Stock</span>
                            <span v-else>Out of Stock</span>
                        </span>
                    </p>

                    <div class="transition-all duration-300 opacity-0 group-hover:bottom-0 group-hover:opacity-100 max-sm:opacity-100">
                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <span 
                                class="absolute top-5 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                tabindex="0"
                                :class="product.is_wishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                @click="addToWishlist()"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                            <span
                                class="icon-compare absolute top-16 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                tabindex="0"
                                @click="addToCompare(product.id)"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                    </div>
                </div>
            </div>

            <div class="grid content-start gap-4">
                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p
                    class="text-[15px] leading-6 text-justify break-words max-h-12"
                    :title="product.name"
                    style="display: -webkit-box; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 2; line-clamp: 2;"
                >
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="flex gap-2.5 text-lg font-semibold"
                    v-html="displayPriceHtml"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Payment Method Pricing -->
                <div class="mt-2 space-y-1.5 text-xs text-gray-600">
                    <!-- Payzy with Logo -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-1">
                        <div class="flex items-center gap-1">
                            <img 
                                v-if="payzyLogo" 
                                :src="payzyLogo" 
                                alt="Payzy" 
                                class="h-4 w-auto max-w-[60px] object-contain"
                            />
                            <span v-else class="font-medium">Payzy (4x):</span>
                            <span v-if="payzyLogo" class="font-medium text-[10px]">(4x):</span>
                        </div>
                        <span class="font-semibold text-gray-800">@{{ formatPrice(getPayzyInstallment()) }} / month</span>
                    </div>
                    
                    <!-- KOKO with Logo -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            <img 
                                v-if="kokoLogo" 
                                :src="kokoLogo" 
                                alt="KOKO" 
                                class="h-4 w-auto max-w-[60px] object-contain"
                            />
                            <span v-else class="font-medium">KOKO (3x):</span>
                            <span v-if="kokoLogo" class="font-medium text-[10px]">(3x):</span>
                        </div>
                        <span class="font-semibold text-gray-800">@{{ formatPrice(getKokoInstallment()) }} / month</span>
                    </div>
                </div>

                <!-- Needs to implement that in future (kept hidden for now) -->
                <div class="hidden gap-4">
                    <span class="block h-[30px] w-[30px] rounded-full bg-[#B5DCB4]">
                    </span>

                    <span class="block h-[30px] w-[30px] rounded-full bg-zinc-500">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                <p class="text-sm text-zinc-500">
                    <template v-if="! product.ratings.total">
                        <p class="text-sm text-zinc-500">
                            @lang('shop::app.components.products.card.review-description')
                        </p>
                    </template>

                    <template v-else>
                        @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.ratings.total"
                                ::rating="false"
                            />
                        @else
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.reviews.total"
                                ::rating="false"
                            />
                        @endif
                    </template>
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <div class="flex gap-2">
                        <x-shop::button
                            class="secondary-button whitespace-nowrap px-8 py-2.5"
                            ::class="getAddToCartButtonClass()"
                            ::title="getAddToCartButtonText()"
                            ::loading="isAddingToCart"
                            ::disabled="isProductOutOfStock() || isAddingToCart"
                            @click="addToCart()"
                        />

                        <x-shop::button
                            class="primary-button whitespace-nowrap px-8 py-2.5"
                            ::class="getAddToCartButtonClass()"
                            title="Buy Now"
                            ::loading="isAddingToCart"
                            ::disabled="isProductOutOfStock() || isAddingToCart"
                            @click="buyNow()"
                        />
                    </div>

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}

                @endif
            </div>
        </div>
    </script>

    <script type="module">
        const INSTALLMENT_SHIPPING_FEE = 500;

        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,

                    payzyLogo: null,

                    kokoLogo: null,

                    shippingCharge: INSTALLMENT_SHIPPING_FEE,
                }
            },

            mounted() {
                this.fetchPaymentLogos();
            },

            computed: {
                displayPriceHtml() {
                    const html = this.product?.price_html ?? '';

                    if (! html) {
                        return html;
                    }

                    const normalizeSymbol = (symbol = '') => {
                        const trimmed = (symbol || '').trim();
                        const rupeeSymbols = ['₨', '₹', 'रु', 'रू', 'Rs', 'Rs.', 'RS', 'RS.', 'rs', 'rs.'];

                        if (rupeeSymbols.includes(trimmed)) {
                            return 'Rs';
                        }

                        return trimmed;
                    };

                    const ensureSymbolLeft = (value) => {
                        if (! value) {
                            return value;
                        }

                        const normalizedSpace = value.replace(/\u00a0/g, ' ');

                        let adjusted = normalizedSpace.replace(
                            /(\d[\d.,]*)(?:\s*)(Rs\.?|₨|₹|रु|रू)/gi,
                            (_match, amount, symbol) => `${normalizeSymbol(symbol)} ${amount}`.trim()
                        );

                        adjusted = adjusted.replace(
                            /(Rs\.?|₨|₹|रु|रू)(?:\s*)(?=\d)/gi,
                            (_match, symbol) => `${normalizeSymbol(symbol)} `
                        );

                        return adjusted.replace(/\s{2,}/g, ' ');
                    };

                    const container = document.createElement('div');

                    container.innerHTML = html;

                    const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null);

                    const textNodes = [];

                    while (walker.nextNode()) {
                        textNodes.push(walker.currentNode);
                    }

                    textNodes.forEach((node) => {
                        node.textContent = ensureSymbolLeft(node.textContent);
                    });

                    return container.innerHTML;
                },
            },

            methods: {
                fetchPaymentLogos() {
                    // Get Payzy logo from core config
                    const payzyImage = "{{ core()->getConfigData('sales.payment_methods.payzy.image') }}";
                    if (payzyImage) {
                        this.payzyLogo = "{{ asset('storage') }}/" + payzyImage;
                    }

                    const kokoImage = "{{ core()->getConfigData('sales.payment_methods.koko.image') }}";
                    if (kokoImage) {
                        this.kokoLogo = "{{ asset('storage') }}/" + kokoImage;
                    } else {
                        this.kokoLogo = "{{ asset('storage/logos/kokologo.png') }}";
                    }
                },
                getStockBadgeStyle() {
                    if (this.isPreorder()) {
                        return 'background-color:#2563eb';
                    }

                    // Check if product has quantity information
                    if (this.product.quantity !== undefined) {
                        if (this.product.quantity <= 0) {
                            return 'background-color:#dc2626'; // Red for out of stock
                        } else if (this.product.quantity <= 5) {
                            return 'background-color:#f59e0b'; // Orange for low stock
                        } else {
                            return 'background-color:#16a34a'; // Green for in stock
                        }
                    }
                    
                    // Fallback to is_saleable check
                    return (this.product.is_saleable !== undefined ? this.product.is_saleable : true) 
                        ? 'background-color:#16a34a' 
                        : 'background-color:#dc2626';
                },

                getBasePrice() {
                    // Extract numeric price from product
                    if (this.product.prices && this.product.prices.final) {
                        return parseFloat(this.product.prices.final.price);
                    }
                    // Fallback: try to extract from price_html
                    if (this.product.price_html) {
                        const priceMatch = this.product.price_html.match(/[\d,]+\.?\d*/);
                        if (priceMatch) {
                            return parseFloat(priceMatch[0].replace(/,/g, ''));
                        }
                    }
                    return 0;
                },

                getShippingCharge() {
                    return Number.isFinite(this.shippingCharge) ? this.shippingCharge : 0;
                },

                getPayzyInstallment() {
                    const basePrice = this.getBasePrice();
                    const shipping = this.getShippingCharge();
                    const baseAmount = basePrice + shipping;
                    const totalWithCharge = baseAmount * 1.14; // 14% charge
                    return totalWithCharge / 4; // Divide into 4 installments
                },

                getKokoInstallment() {
                    const basePrice = this.getBasePrice();
                    const shipping = this.getShippingCharge();
                    const baseAmount = basePrice + shipping;
                    const totalWithCharge = baseAmount * 1.12; // 12% charge
                    return totalWithCharge / 3; // Divide into 3 installments
                },

                formatPrice(price) {
                    // Format price with currency symbol
                    const rupeeSymbols = ['₨', '₹', 'रु', 'रू', 'Rs', 'Rs.', 'RS', 'RS.', 'rs', 'rs.'];
                    const matchSymbol = this.product.prices?.final?.formatted_price?.match(/[^\d,.\s]+/)?.[0];
                    const symbol = matchSymbol ? matchSymbol.trim() : 'Rs';
                    const normalizedSymbol = rupeeSymbols.includes(symbol) ? 'Rs' : symbol;

                    return `${normalizedSymbol} ${price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
                },

                getStockBadgeText() {
                    if (this.isPreorder()) {
                        return '@lang('shop::app.components.products.card.pre-order')';
                    }

                    // Check if product has quantity information
                    if (this.product.quantity !== undefined) {
                        if (this.product.quantity <= 0) {
                            return '@lang('shop::app.components.products.card.out-of-stock')';
                        } else if (this.product.quantity <= 5) {
                            return `Low Stock (${this.product.quantity})`;
                        } else {
                            return '@lang('shop::app.components.products.card.in-stock')';
                        }
                    }
                    
                    // Fallback to is_saleable check
                    if (this.product.is_saleable !== undefined ? this.product.is_saleable : true) {
                        return '@lang('shop::app.components.products.card.in-stock')';
                    } else {
                        return '@lang('shop::app.components.products.card.out-of-stock')';
                    }
                },

                isProductOutOfStock() {
                    if (this.isPreorder()) {
                        return false;
                    }

                    // Check if product has quantity information and is out of stock
                    if (this.product.quantity !== undefined) {
                        return this.product.quantity <= 0;
                    }
                    // Fallback to is_saleable check
                    return !(this.product.is_saleable !== undefined ? this.product.is_saleable : true);
                },

                isPreorder() {
                    return Boolean(this.product?.is_preorder ?? this.product?.preorder);
                },

                getAddToCartButtonClass() {
                    if (this.isProductOutOfStock()) {
                        return 'opacity-50 cursor-not-allowed bg-gray-400 border-gray-400';
                    }
                    return '';
                },

                getAddToCartButtonText() {
                    if (this.isProductOutOfStock()) {
                        return '@lang('shop::app.components.products.card.out-of-stock')';
                    }
                    return '@lang('shop::app.components.products.card.add-to-cart')';
                },

                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

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
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }
                            
                            this.isAddingToCart = false;
                        });
                },

                buyNow() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                // Redirect to checkout page
                                window.location.href = '{{ route("shop.checkout.onepage.index") }}';
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                this.isAddingToCart = false;
                            }
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }
                            
                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce
