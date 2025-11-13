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
    
    <div class="mx-auto w-full max-w-[1350px] px-4 sm:px-6 lg:px-8">
        <!-- Loop over the theme customization -->
        @foreach ($customizations as $customization)
            @php ($data = $customization->options) @endphp

            <!-- Static content -->
            @switch ($customization->type)
                @case ($customization::IMAGE_CAROUSEL)
                    <!-- Image Carousel -->
                    <x-shop::carousel :options="$data" aria-label="Image Carousel" />

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
                    <!-- Categories carousel -->
                    <x-shop::categories.carousel
                        :title="$data['title'] ?? ''"
                        :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.home.index')"
                        aria-label="Categories Carousel"
                    />

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
        @endforeach
    </div>
</x-shop::layouts>
