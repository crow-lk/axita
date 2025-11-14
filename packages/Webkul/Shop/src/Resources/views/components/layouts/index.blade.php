@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >

        @stack('meta')

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link
            rel="preload"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
            as="style"
        >
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        >

        <link
            rel="preload"
            href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
            as="style"
        >
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        >

        <!-- FontAwesome Icons - Direct inclusion -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
            integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        <!-- Floating Action Buttons Styles -->
        <style>
            .floating-action-buttons {
                animation: fadeInUp 0.6s ease-out;
                align-items: center;
            }

            .floating-action-buttons a, .floating-action-buttons button {
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Contact Group Styling - Larger buttons */
            .contact-group {
                padding: 0.5rem;
                border-radius: 1rem;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(5px);
            }

            /* Social Group Styling - Medium buttons */
            .social-group {
                padding: 0.5rem;
                border-radius: 1rem;
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(5px);
            }

            /* Navigation Group Styling - Small button */
            .navigation-group {
                padding: 0.25rem;
                border-radius: 0.75rem;
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(5px);
            }

            .whatsapp-btn {
                background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            }

            .whatsapp-btn:hover {
                background: linear-gradient(135deg, #128c7e 0%, #075e54 100%);
                transform: scale(1.1) rotate(5deg);
            }

            .call-btn {
                background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            }

            .call-btn:hover {
                background: linear-gradient(135deg, #c2410c 0%, #9a3412 100%);
                transform: scale(1.1) rotate(-5deg);
            }

            .facebook-btn {
                background: linear-gradient(135deg, #1877f2 0%, #166fe5 100%);
            }

            .facebook-btn:hover {
                background: linear-gradient(135deg, #166fe5 0%, #1458c7 100%);
                transform: scale(1.1) rotate(5deg);
            }

            .instagram-btn {
                background: linear-gradient(135deg, #e4405f 0%, #833ab4 50%, #fcb045 100%);
            }

            .instagram-btn:hover {
                background: linear-gradient(135deg, #d62d4f 0%, #7e2dad 50%, #f0a23d 100%);
                transform: scale(1.1) rotate(-5deg);
            }

            .tiktok-btn {
                background: linear-gradient(135deg, #000000 0%, #25292e 100%);
            }

            .tiktok-btn:hover {
                background: linear-gradient(135deg, #25292e 0%, #161823 100%);
                transform: scale(1.1) rotate(5deg);
            }

            .scroll-top-btn {
                background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .scroll-top-btn:hover {
                background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
                transform: scale(1.1) translateY(-2px);
                color: white;
            }

            /* Group animations - staggered entrance */
            .contact-group {
                animation: slideInRight 0.6s ease-out 0.1s both;
            }

            .social-group {
                animation: slideInRight 0.6s ease-out 0.3s both;
            }

            .navigation-group {
                animation: slideInRight 0.6s ease-out 0.5s both;
            }

            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .floating-action-buttons a::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                border-radius: 50%;
                background: inherit;
                opacity: 0;
                transform: scale(0.8);
                transition: all 0.3s ease;
            }

            .floating-action-buttons a:hover::before {
                opacity: 0.3;
                transform: scale(1.2);
            }

            /* Pulse animation */
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }

            .floating-action-buttons a {
                animation: pulse 2s infinite;
            }

            .floating-action-buttons a:hover {
                animation: none;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .floating-action-buttons {
                    right: 0.25rem;
                    top: 50%;
                    transform: translateY(-50%);
                }
                
                .contact-group a {
                    height: 2.25rem !important;
                    width: 2.25rem !important;
                }
                
                .social-group a {
                    height: 2rem !important;
                    width: 2rem !important;
                }
                
                .navigation-group button {
                    height: 1.75rem !important;
                    width: 1.75rem !important;
                }
                
                .floating-action-buttons .fab, .floating-action-buttons .fas {
                    font-size: 0.875rem !important;
                }
                
                .navigation-group .fas {
                    font-size: 0.75rem !important;
                }
            }
            
            /* Mobile: Remove margins/paddings for category sidebar only */
            @media (max-width: 768px) {
                /* Target the sidebar wrapper and its main sidebar elements */
                #category-sidebar-wrapper,
                #category-sidebar-wrapper * {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .category-sidebar,
                .fallback-sidebar,
                #mobile-category-sidebar {
                    width: 100% !important;
                    max-width: 100% !important;
                    left: 0 !important;
                    position: static !important;
                    box-shadow: none !important;
                    border: none !important;
                    background: transparent !important;
                }

                /* Ensure main content uses full width on mobile */
                #main-content {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
            }

            /* Desktop positioning - very close to edge */
            @media (min-width: 769px) {
                .floating-action-buttons {
                    right: 0.25rem; /* Very close to screen edge */
                }
                
                .contact-group a {
                    height: 2.5rem;
                    width: 2.5rem;
                }
                
                .social-group a {
                    height: 2.25rem;
                    width: 2.25rem;
                }
                
                .navigation-group button {
                    height: 2rem;
                    width: 2rem;
                }
            }

            /* Large screens - minimal space from edge */
            @media (min-width: 1400px) {
                .floating-action-buttons {
                    right: 0.5rem;
                }
            }

            /* Hide the category sidebar on Nest Hub (1024x600) devices.
               Use max-width so wider screens (eg. 1440px) still show the sidebar. */
            @media (max-width: 1024px) and (max-height: 600px) {
                .hide-on-nesthub {
                    display: none !important;
                }

                /* Ensure main content expands when sidebar hidden on Nest Hub */
                #main-content {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
            }

            /* Show sidebar only on very large screens (>=1400px) */
            .show-on-1400 {
                display: none !important;
            }

            @media (min-width: 1400px) {
                .show-on-1400 {
                    display: block !important;
                }
            }

            /* Ensure buttons stay above everything */
            .floating-action-buttons {
                z-index: 9999;
            }
        </style>

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop::layouts.header />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <!-- Category Sidebar (visible only on very large screens: >=1400px) -->
            <div class="show-on-1400 hide-on-nesthub">
                <x-shop::layouts.sidebar.category-sidebar />
            </div>

            <div
                id="main-content"
                class="w-full max-w-[1400px] mx-auto px-1 sm:px-6 lg:px-8 transition-all duration-300"
            >
                <!-- Page Content Blade Component -->
                <main id="main" class="bg-white min-h-screen pt-16 md:pt-0">
                    {{ $slot }}
                </main>

                {!! view_render_event('bagisto.shop.layout.content.after') !!}

                <!-- Page Services Blade Component -->
                @if ($hasFeature)
                    <x-shop::layouts.services />
                @endif
            </div>

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif

            <!-- Floating Action Buttons -->
            <div class="floating-action-buttons fixed right-1 top-1/2 transform -translate-y-1/2 z-50 flex-col items-center gap-4 hidden md:flex">
                <!-- Contact Group -->
                <div class="contact-group flex flex-col gap-3">
                    <!-- WhatsApp Button -->
                    <a 
                        href="https://wa.me/94771284323?text=Hello, I'm interested in your products" 
                        target="_blank"
                        class="whatsapp-btn flex h-10 w-10 items-center justify-center rounded-full bg-green-500 text-white shadow-lg transition-all duration-300 hover:bg-green-600 hover:scale-110 hover:shadow-xl"
                        title="Chat on WhatsApp"
                    >
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>

                    <!-- Call Now Button -->
                    <a 
                        href="tel:+94771284323" 
                        class="call-btn flex h-10 w-10 items-center justify-center rounded-full bg-orange-600 text-white shadow-lg transition-all duration-300 hover:bg-orange-700 hover:scale-110 hover:shadow-xl"
                        title="Call Now"
                    >
                        <i class="fas fa-phone text-base"></i>
                    </a>
                </div>

                <!-- Social Media Group -->
                <div class="social-group flex flex-col gap-3">
                    <!-- Facebook Button -->
                    <a 
                        href="https://www.facebook.com/axitacomputers" 
                        target="_blank"
                        class="facebook-btn flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg transition-all duration-300 hover:bg-blue-700 hover:scale-110 hover:shadow-xl"
                        title="Follow us on Facebook"
                    >
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>

                    <!-- Instagram Button -->
                    <a 
                        href="https://www.instagram.com/axita_computer/" 
                        target="_blank"
                        class="instagram-btn flex h-9 w-9 items-center justify-center rounded-full bg-pink-500 text-white shadow-lg transition-all duration-300 hover:bg-pink-600 hover:scale-110 hover:shadow-xl"
                        title="Follow us on Instagram"
                    >
                        <i class="fab fa-instagram text-sm"></i>
                    </a>

                    <!-- TikTok Button -->
                    <a 
                        href="https://www.tiktok.com/@axita.galle" 
                        target="_blank"
                        class="tiktok-btn flex h-9 w-9 items-center justify-center rounded-full bg-black text-white shadow-lg transition-all duration-300 hover:bg-gray-800 hover:scale-110 hover:shadow-xl"
                        title="Follow us on TikTok"
                    >
                        <i class="fab fa-tiktok text-sm"></i>
                    </a>
                </div>

                <!-- Navigation Group -->
                <div class="navigation-group flex flex-col">
                    <!-- Scroll to Top Button -->
                    <button 
                        id="scroll-to-top-btn"
                        class="scroll-top-btn flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-white shadow-lg transition-all duration-300 hover:bg-orange-600 hover:scale-110 hover:shadow-xl opacity-70 hover:opacity-100"
                        title="Back to Top"
                        onclick="scrollToTop()"
                    >
                        <i class="fas fa-chevron-up text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            /**
             * Load event, the purpose of using the event is to mount the application
             * after all of our `Vue` components which is present in blade file have
             * been registered in the app. No matter what `app.mount()` should be
             * called in the last.
             */
            window.addEventListener("load", function (event) {
                app.mount("#app");
            });

            /**
             * Smooth scroll to top functionality
             */
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            /**
             * Show/hide scroll to top button based on scroll position
             */
            window.addEventListener('scroll', function() {
                const scrollBtn = document.getElementById('scroll-to-top-btn');
                if (scrollBtn) {
                    if (window.pageYOffset > 300) {
                        scrollBtn.style.opacity = '0.7';
                        scrollBtn.style.pointerEvents = 'auto';
                    } else {
                        scrollBtn.style.opacity = '0.3';
                        scrollBtn.style.pointerEvents = 'auto';
                    }
                }
            });
        </script>

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
        <script src="https://widget.tagembed.com/embed.min.js" type="text/javascript"></script>
    </body>
</html>
