{!! view_render_event('bagisto.shop.layout.header.before') !!}

@if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
    </div>
@endif

<header id="main-header" class="shadow-gray fixed top-0 left-0 right-0 z-50 bg-white shadow-sm transition-transform duration-300 max-lg:shadow-none">
    <x-shop::layouts.header.desktop />

    <x-shop::layouts.header.mobile />
</header>

<!-- Add spacing to prevent content jump -->
<div id="header-spacer"></div>

<script>
    (function() {
        let lastScrollTop = 0;
        const header = document.getElementById('main-header');
        const spacer = document.getElementById('header-spacer');
        let headerHeight = 0;

        // Set spacer height after header loads
        window.addEventListener('load', function() {
            headerHeight = header.offsetHeight;
            spacer.style.height = headerHeight + 'px';
        });

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Update header height if it changed
            if (headerHeight === 0) {
                headerHeight = header.offsetHeight;
                spacer.style.height = headerHeight + 'px';
            }

            if (currentScroll > lastScrollTop && currentScroll > headerHeight) {
                // Scrolling down - hide header
                header.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up - show header
                header.style.transform = 'translateY(0)';
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);
    })();
</script>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
