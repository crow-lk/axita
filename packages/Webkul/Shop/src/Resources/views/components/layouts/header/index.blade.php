{!! view_render_event('bagisto.shop.layout.header.before') !!}

@if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
    <div class="max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
    </div>
@endif

<header id="main-header" class="shadow-gray sticky top-0 left-0 right-0 z-50 bg-white shadow-sm max-lg:shadow-none">
    <x-shop::layouts.header.desktop />

    <x-shop::layouts.header.mobile />
</header>

<!-- Add spacing to prevent content jump -->
<div id="header-spacer"></div>

<!-- Spacer for sticky header -->
<script>
    window.addEventListener('load', function() {
        const header = document.getElementById('main-header');
        const spacer = document.getElementById('header-spacer');
        if (header && spacer) {
            spacer.style.height = header.offsetHeight + 'px';
        }
    });
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
