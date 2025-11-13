<x-shop::layouts>
    <x-slot:title>
        Payment Methods - Axita Computers
    </x-slot>

    @php
        $methodBlueprints = [
            'cashondelivery' => [
                'title'         => 'Cash on Delivery',
                'description'   => 'Pay with cash when our courier hands over your item or when you pick it up in-store.',
                'fallback_logo' => bagisto_asset('images/cash-on-delivery.png', 'shop'),
                'bullets'       => [
                    'Available for AXITA deliveries across the Southern Province.',
                    'Driver issues a printed receipt once payment is collected.',
                    'Ideal for regular customers who prefer physical verification before paying.',
                ],
            ],

            'moneytransfer' => [
                'title'         => 'Bank Transfer / Deposit',
                'description'   => 'Settle your invoice via online banking or direct branch deposit and share the slip with us.',
                'fallback_logo' => bagisto_asset('images/money-transfer.png', 'shop'),
                'bullets'       => [
                    'Supported by leading local banks including Sampath and HNB.',
                    'Orders are reserved for 24 hours while we await proof of transfer.',
                    'Great for corporate procurement that needs internal approvals.',
                ],
            ],

            'payhere' => [
                'title'         => 'PayHere Online Payments',
                'description'   => 'Use any Sri Lankan issued Visa, MasterCard, or Amex card with 3D Secure protection.',
                'fallback_logo' => bagisto_asset('images/payhere.png', 'shop'),
                'bullets'       => [
                    'Hosted checkout with tokenized card handling and OTP verification.',
                    'Instant confirmation sent to your email and customer account.',
                    'Supports recurring invoices for service retainers.',
                ],
            ],

            'payzy' => [
                'title'       => 'Payzy Secure Gateway',
                'description' => 'Fast card processing through Payzy with automatic status updates in your order timeline.',
                'bullets'     => [
                    'Accepts local and international Visa/Master cards.',
                    'Fraud-screened sessions with callback verification.',
                    'Preferred for large-value hardware purchases that need live tracking.',
                ],
            ],

            'koko' => [
                'title'         => 'KOKO Buy Now – Pay Later',
                'description'   => 'Split your purchase into three zero-interest payments using the KOKO app.',
                'fallback_logo' => asset('storage/logos/kokologo.png'),
                'bullets'       => [
                    'Instant digital approval for shoppers with an active KOKO wallet.',
                    'First installment is billed immediately, the rest auto-deduct every 30 days.',
                    'Perfect for students and creators investing in new laptops or peripherals.',
                ],
            ],
        ];

        $paymentCards = [];

        foreach ($methodBlueprints as $code => $blueprint) {
            $configBase = "sales.payment_methods.$code";

            $active = core()->getConfigData("$configBase.active");

            if (is_null($active)) {
                $active = config("payment_methods.$code.active", true);
            }

            if (! (bool) $active) {
                continue;
            }

            $title = core()->getConfigData("$configBase.title") ?? config("payment_methods.$code.title") ?? $blueprint['title'];

            $description = core()->getConfigData("$configBase.description") ?? $blueprint['description'];

            $imagePath = core()->getConfigData("$configBase.image");

            $logo = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : ($blueprint['fallback_logo'] ?? null);

            $badge = strtoupper(\Illuminate\Support\Str::of($title)->replaceMatches('/[^A-Z]/i', '')->substr(0, 2));

            $paymentCards[] = [
                'code'        => $code,
                'title'       => $title,
                'description' => $description,
                'logo'        => $logo,
                'badge'       => $badge,
                'bullets'     => $blueprint['bullets'],
            ];
        }
    @endphp

    <div class="mx-auto max-w-6xl space-y-10 px-4 py-10 sm:px-6 lg:px-8">
        <!-- Hero -->
        <section class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl sm:p-10 lg:p-12">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/60">
                        Secure & Local Friendly
                    </p>

                    <h1 class="mt-4 text-4xl font-bold leading-tight lg:text-5xl">
                        Simple payment options for every kind of customer
                    </h1>

                    <p class="mt-4 text-base text-white/80">
                        Choose cash, bank transfer, card gateways, or installment plans. Each option below is already configured inside our
                        storefront and can be selected during checkout or arranged with our sales desk.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            href="{{ route('shop.home.contact_us') }}"
                            class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                        >
                            Talk to Billing Support
                            <span class="ml-2 text-2xl">→</span>
                        </a>

                        <div class="flex items-center gap-4 rounded-2xl border border-white/30 px-5 py-3 text-sm text-white/80">
                            <div class="text-3xl">🔒</div>
                            <div>
                                <p class="font-semibold text-white">PCI-DSS Ready</p>
                                <p>SSL encrypted checkout & signed callbacks</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1 flex-wrap gap-4">
                    <div class="min-w-[160px] flex-1 rounded-2xl border border-white/20 bg-white/10 p-5 text-center">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Realtime</p>
                        <p class="mt-3 text-3xl font-bold">Status</p>
                        <p class="text-xs text-white/70">Orders auto-update after each payment event.</p>
                    </div>

                    <div class="min-w-[160px] flex-1 rounded-2xl border border-white/20 bg-white/10 p-5 text-center">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Support</p>
                        <p class="mt-3 text-3xl font-bold">24/7</p>
                        <p class="text-xs text-white/70">Billing hotline answers within 15 minutes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Payment methods -->
        <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="mb-8 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Available Methods</p>
                <h2 class="mt-4 text-3xl font-bold text-slate-900">Pick the way you’d like to pay</h2>
                <p class="mt-4 text-base text-slate-600">
                    The options below mirror the choices you’ll see during checkout. Uploads that you have stored in
                    <strong>Settings → Sales → Payment Methods</strong> are displayed automatically.
                </p>
            </div>

            @if (empty($paymentCards))
                <p class="text-center text-slate-600">
                    No payment methods are enabled right now. Please activate at least one method in the admin panel.
                </p>
            @else
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($paymentCards as $method)
                        <article class="rounded-2xl border border-slate-100/70 p-6 transition hover:-translate-y-1 hover:border-orange-200 hover:shadow-lg">
                            <div class="flex items-center gap-4">
                                @if ($method['logo'])
                                    <div class="flex h-14 w-20 items-center justify-center rounded-2xl border border-slate-100 bg-white p-2 shadow-sm">
                                        <img
                                            src="{{ $method['logo'] }}"
                                            alt="{{ $method['title'] }} logo"
                                            class="h-full w-full object-contain"
                                            loading="lazy"
                                        >
                                    </div>
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-lg font-bold text-orange-600">
                                        {{ $method['badge'] }}
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-xl font-semibold text-slate-900">{{ $method['title'] }}</h3>
                                    <p class="text-sm text-slate-500">Code: {{ $method['code'] }}</p>
                                </div>
                            </div>

                            <p class="mt-4 text-base text-slate-600">
                                {{ $method['description'] }}
                            </p>

                            <ul class="mt-4 space-y-3 text-sm text-slate-700">
                                @foreach ($method['bullets'] as $point)
                                    <li class="flex gap-3">
                                        <span class="text-green-500">✓</span>
                                        <span>{{ $point }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Security -->
        <section class="rounded-3xl border border-slate-100 bg-slate-50 p-8 shadow-sm sm:p-10">
            <div class="mb-8 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Security Layers</p>
                <h2 class="mt-4 text-3xl font-bold text-slate-900">How we protect every transaction</h2>
                <p class="mt-3 text-base text-slate-600">
                    Each provider above plugs into the same monitoring stack so finance, support, and customers see the same data.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @php
                    $securityCards = [
                        ['title' => 'SSL & HSTS', 'copy' => 'All checkout and callback URLs run behind TLS 1.2 with HTTP Strict Transport Security.'],
                        ['title' => 'PCI aligned', 'copy' => 'Gateways handle card data; we store only references and signed payloads.'],
                        ['title' => 'Realtime alerts', 'copy' => 'Webhook failures, refund attempts, and chargebacks ping our 24/7 Slack channel.'],
                        ['title' => 'Audit ready', 'copy' => 'Invoices and transfer slips are archived with timestamped staff notes.'],
                    ];
                @endphp

                @foreach ($securityCards as $card)
                    <div class="rounded-2xl border border-white bg-white p-6 text-center shadow">
                        <p class="text-lg font-semibold text-slate-900">{{ $card['title'] }}</p>
                        <p class="mt-3 text-sm text-slate-600">{{ $card['copy'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- CTA -->
        <section class="rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50 via-white to-white p-8 text-center shadow-[0_20px_50px_rgba(8,15,23,0.06)] sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Need a custom plan?</p>
            <h2 class="mt-4 text-3xl font-bold text-slate-900">Our finance desk can reserve stock while your payment clears</h2>
            <p class="mt-4 text-base text-slate-600">
                Email billing@axita.lk or call +94 77 128 4323 with your order number. We’ll attach transfer slips, courier receipts, or gateway
                confirmations directly to your invoice.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a
                    href="tel:+94771284323"
                    class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                >
                    Call Finance
                </a>
                <a
                    href="{{ route('shop.home.contact_us') }}"
                    class="inline-flex items-center rounded-full border border-orange-200 px-6 py-3 font-semibold text-orange-600 transition hover:bg-orange-50"
                >
                    Send Payment Proof
                </a>
            </div>
        </section>
    </div>
</x-shop::layouts>
