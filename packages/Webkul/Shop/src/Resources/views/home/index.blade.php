@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />

    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />

    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

<x-shop::layouts>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    
    <div class="mx-auto w-full">
        @php
            $insertedHeroTrigger = false;
        @endphp

        <!-- Loop over the theme customization -->
        @foreach ($customizations as $customization)
            @php ($data = $customization->options) @endphp

            <!-- Static content -->
            @switch ($customization->type)
                @case ($customization::IMAGE_CAROUSEL)
                    <!-- Image Carousel -->
                    <!-- Add horizontal padding on mobile only so the slider has breathing room -->
                    <div class="px-4 sm:px-0">
                        <x-shop::carousel :options="$data" aria-label="Image Carousel" />
                    </div>

                    @break
                @case ($customization::STATIC_CONTENT)
                    <!-- push style -->
                    @if (! empty($data['css']))
                        @push ('styles')
                            <style>
                                {{ $data['css'] }}
                            </style>
                        @endpush
                    @endif

                    <!-- render html -->
                    @if (! empty($data['html']))
                        {!! $data['html'] !!}
                    @endif

                    @break
                @case ($customization::CATEGORY_CAROUSEL)
                    <!-- Categories carousel (mobile full-bleed) -->
                    <div class="-mx-4 sm:mx-0">
                        <x-shop::categories.carousel
                            :title="$data['title'] ?? ''"
                            :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                            :navigation-link="route('shop.home.index')"
                            aria-label="Categories Carousel"
                        />
                    </div>

                    @break
                @case ($customization::PRODUCT_CAROUSEL)
                    <!-- Product Carousel -->
                    <x-shop::products.carousel
                        :title="$data['title'] ?? ''"
                        :src="route('shop.api.products.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                        aria-label="Product Carousel"
                    />

                    @break
            @endswitch

            @if (! $insertedHeroTrigger)
                <div id="landing-hero-trigger" class="h-px w-full"></div>

                @php
                    $insertedHeroTrigger = true;
                @endphp
            @endif
        @endforeach
    </div>

    @pushOnce('styles')
        <style>
            #landing-secondary-header.landing-secondary-visible {
                opacity: 1;
                pointer-events: auto;
                transform: translateY(0);
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1);
            }
        </style>
    @endPushOnce

    <div
        id="landing-secondary-header"
        class="sticky top-[88px] z-40 mt-4 opacity-0 pointer-events-none -translate-y-2 transition-all duration-300"
    >
        <div class="mx-auto flex w-full max-w-[1350px] items-center justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/95 px-4 py-3 text-sm font-semibold text-zinc-700 shadow-sm backdrop-blur">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('shop.home.services') }}" class="transition hover:text-[#e85805]">Services</a>
                <a href="{{ route('shop.search.index', ['query' => 'deals']) }}" class="transition hover:text-[#e85805]">Best Deals</a>
                <a href="{{ route('shop.home.payment_methods') }}" class="transition hover:text-[#e85805]">Payment Methods</a>
                <a href="{{ route('shop.home.about_us') }}" class="transition hover:text-[#e85805]">About Us</a>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="tel:+94771284323"
                    class="hidden items-center gap-2 rounded-full border border-orange-200 px-4 py-2 text-xs font-semibold text-orange-600 transition hover:bg-orange-50 sm:flex"
                >
                    <span class="icon-phone text-base"></span>
                    +94 77 128 4323
                </a>

                <a
                    href="{{ route('shop.home.contact_us') }}"
                    class="inline-flex items-center rounded-full bg-orange-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-orange-400"
                >
                    Contact Us
                </a>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="module">
            (() => {
                const bar = document.getElementById('landing-secondary-header');

                if (! bar) {
                    return;
                }

                const toggleBar = () => {
                    if (window.scrollY >= 160) {
                        bar.classList.add('landing-secondary-visible');
                        bar.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-2');
                    } else {
                        bar.classList.remove('landing-secondary-visible');
                        bar.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
                    }
                };

                window.addEventListener('scroll', toggleBar, { passive: true });
                toggleBar();
            })();
        </script>
    @endPushOnce
</x-shop::layouts>
