{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!-- Reimagined footer layout with curated content for AXITA storefront. -->
@php
    $channel = core()->getCurrentChannel();
    $footerLogo = $channel?->logo_url ?? bagisto_asset('images/logo.svg');
@endphp
<footer class="mt-12 bg-[#080808] text-white max-sm:mt-10">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-black via-[#111827] to-[#1F2937] opacity-95"></div>

        <div class="relative mx-auto w-full max-w-7xl px-6 py-16 lg:px-12">
            <div class="grid gap-12 text-white/70 sm:grid-cols-2 xl:grid-cols-4">
                <div class="space-y-5 text-sm">
                    <a
                        href="{{ route('shop.home.index') }}"
                        class="inline-flex items-center gap-3"
                    >
                        <img
                            src="{{ $footerLogo }}"
                            alt="{{ $channel?->name ?? config('app.name') }}"
                            loading="lazy"
                            class="h-12 w-auto object-contain"
                        >
                    </a>

                    <p class="max-w-md text-base leading-relaxed text-white/80">
                        Discover cutting-edge tech, trusted warranty support, and expert service from AXITA Computers.
                    </p>
                </div>

                <div class="text-base">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-300">Links</h3>

                    <ul class="mt-4 space-y-2">
                        <li>
                            <a
                                href="{{ route('shop.cms.page', 'about-us') }}"
                                class="transition-colors duration-200 hover:text-white"
                            >
                                About Us
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('shop.home.contact_us') }}"
                                class="transition-colors duration-200 hover:text-white"
                            >
                                Contact Us
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('shop.cms.page', 'terms-conditions') }}"
                                class="transition-colors duration-200 hover:text-white"
                            >
                                Terms &amp; Conditions
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('shop.cms.page', 'refund-policy') }}"
                                class="transition-colors duration-200 hover:text-white"
                            >
                                Refund Policy
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('shop.cms.page', 'privacy-policy') }}"
                                class="transition-colors duration-200 hover:text-white"
                            >
                                Privacy Policy
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="text-base">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-300">Brands</h3>

                    <ul class="mt-4 space-y-2">
                        <li>MSI</li>
                        <li>ASUS</li>
                        <li>ACER</li>
                        <li>HP</li>
                        <li>TOSHIBA</li>
                        <li>DELL</li>
                        <li>OSCO</li>
                        <li>LEXER</li>
                    </ul>
                </div>

                <div class="space-y-6 text-base">
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-300">Contact</h3>

                        <p>Follow us on social media</p>

                        <div class="flex items-center gap-4 text-white">
                            <a
                                href="https://www.facebook.com/axitacomputers"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-200 hover:-translate-y-1 hover:bg-white/20"
                                aria-label="Facebook"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>

                            <a
                                href="https://www.instagram.com/axita_computer/"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-200 hover:-translate-y-1 hover:bg-white/20"
                                aria-label="Instagram"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="fab fa-instagram text-lg"></i>
                            </a>

                            <a
                                href="https://wa.me/94771284323"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-200 hover:-translate-y-1 hover:bg-white/20"
                                aria-label="WhatsApp"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="fab fa-whatsapp text-lg"></i>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-2 text-white">
                        <p class="text-sm uppercase tracking-[0.2em] text-white/60">Phone</p>

                        <a
                            href="tel:+94771284323"
                            class="text-2xl font-semibold transition-colors duration-200 hover:text-gray-300"
                        >
                            +94 77 128 4323
                        </a>
                    </div>

                    <div class="space-y-2 text-white/80">
                        <p class="text-sm uppercase tracking-[0.2em] text-white/60">Address</p>

                        <p>
                            Beligaha Junction, Galle, Sri Lanka
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10 bg-transparent">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="mx-auto max-w-7xl px-6 py-6 text-center text-xs uppercase tracking-[0.3em] text-gray-400 lg:px-12">
            © 2025 All rights reserved | Designed &amp; maintained by AXITA Computers Pvt Ltd | Powered by crow.lk
        </p>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
