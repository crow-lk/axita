<x-shop::layouts>
    <x-slot:title>
        About Us - Axita Computers
    </x-slot>

    <div class="mx-auto max-w-6xl space-y-10 px-4 py-10 sm:px-6 lg:px-8">
        <!-- Hero -->
        <section class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl sm:p-10 lg:p-12">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/60">
                        Galle · Southern Province
                    </p>

                    <h1 class="mt-4 text-4xl font-bold leading-tight lg:text-5xl">
                        The IT shop and repair team trusted by homes, gamers, and business owners
                    </h1>

                    <p class="mt-4 text-base text-white/80">
                        For more than a decade, Axita Computers has combined retail, repair, and on-site support so customers do not have
                        to juggle multiple vendors. We pair genuine components with honest advice, keeping people connected, secure,
                        and productive.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            href="{{ route('shop.home.services') }}"
                            class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                        >
                            Explore Our Services
                            <span class="ml-2 text-2xl">→</span>
                        </a>

                        <div class="flex items-center gap-4 rounded-2xl border border-white/30 px-5 py-3 text-sm text-white/80">
                            <div class="text-3xl">🏪</div>
                            <div>
                                <p class="font-semibold text-white">Showroom + Lab</p>
                                <p>Walk-in diagnostics 6 days a week</p>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $aboutStats = [
                        ['label' => 'Year founded', 'value' => '2010'],
                        ['label' => 'Devices repaired each month', 'value' => '300+'],
                        ['label' => 'Corporate & school partners', 'value' => '120'],
                    ];
                @endphp

                <div class="flex flex-1 flex-wrap gap-4">
                    @foreach ($aboutStats as $stat)
                        <div class="min-w-[160px] flex-1 rounded-2xl border border-white/15 bg-white/10 p-5 text-center">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">{{ $stat['label'] }}</p>
                            <p class="mt-3 text-3xl font-bold">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Story -->
        <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Our Story</p>
                    <h2 class="mt-4 text-3xl font-bold text-slate-900">Why we built Axita Computers</h2>
                    <p class="mt-4 text-base text-slate-600">
                        We started as a two-person kiosk refurbishing laptops for students in Galle. Demand for reliable servicing,
                        genuine accessories, and on-site support grew quickly, so we invested in a full diagnostics lab, retail floor,
                        and mobile service units. Today we support everything from gaming rigs to school networks and hotel CCTV.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.4em] text-slate-500">Milestones</p>
                    <ul class="mt-4 space-y-4 text-sm text-slate-700">
                        <li>
                            <p class="font-semibold text-slate-900">2010 – Launch</p>
                            <p>Opened our first counter near Beligaha Junction focusing on computer repairs.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">2015 – Business support</p>
                            <p>Introduced annual maintenance contracts for hotels, schools, and retail chains.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">2020 – Integrated ecommerce</p>
                            <p>Rolled out the current Bagisto-powered storefront with in-house logistics tracking.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="rounded-3xl border border-slate-100 bg-slate-50 p-8 shadow-sm sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500 text-center">What guides us</p>
            <h2 class="mt-4 text-center text-3xl font-bold text-slate-900">Values we practice with every ticket</h2>

            @php
                $values = [
                    ['title' => 'Honest diagnostics', 'copy' => 'We share photos, part numbers, and repair options before we touch your device.'],
                    ['title' => 'Local expertise', 'copy' => 'Technicians live in the same towns we serve, so follow-up support is effortless.'],
                    ['title' => 'Genuine parts', 'copy' => 'We only stock brand-certified components and list warranty terms on every invoice.'],
                    ['title' => 'Responsive support', 'copy' => 'WhatsApp, phone, and email channels are monitored beyond store hours.'],
                ];
            @endphp

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                @foreach ($values as $value)
                    <div class="rounded-2xl border border-white bg-white p-6 shadow">
                        <p class="text-lg font-semibold text-slate-900">{{ $value['title'] }}</p>
                        <p class="mt-3 text-sm text-slate-600">{{ $value['copy'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Daily focus -->
        <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="grid gap-8 lg:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Day-to-day</p>
                    <h2 class="mt-4 text-3xl font-bold text-slate-900">How we help customers every day</h2>
                    <p class="mt-4 text-base text-slate-600">
                        We operate a live service queue so customers can drop in, book a courier pickup, or schedule an on-site visit.
                        Retail, repairs, and logistics use the same platform which means updates are in sync.
                    </p>
                </div>

                <ul class="space-y-4 text-slate-700">
                    <li class="flex gap-3">
                        <span class="text-green-500">✓</span>
                        Walk-in troubleshooting bench for laptops, desktops, POS units, and mobile devices.
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-500">✓</span>
                        Field teams that install Wi-Fi, CCTV, and structured cabling across Galle, Matara, and Hambantota.
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-500">✓</span>
                        Procurement support for schools and businesses needing curated device lists with warranty tracking.
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-500">✓</span>
                        Same-day shipping for stocked accessories and urgent replacement parts.
                    </li>
                </ul>
            </div>
        </section>

        <!-- CTA -->
        <section class="rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50 via-white to-white p-8 text-center shadow-[0_20px_50px_rgba(8,15,23,0.06)] sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Visit or message us</p>
            <h2 class="mt-4 text-3xl font-bold text-slate-900">Ready to help with your next upgrade or urgent repair</h2>
            <p class="mt-4 text-base text-slate-600">
                Drop by the Beligaha Junction store, call +94 77 128 4323, or send us a note with photos of your device.
                We will respond with a plan, quote, and timeline.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a
                    href="tel:+94771284323"
                    class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                >
                    Call the Team
                </a>
                <a
                    href="{{ route('shop.home.contact_us') }}"
                    class="inline-flex items-center rounded-full border border-orange-200 px-6 py-3 font-semibold text-orange-600 transition hover:bg-orange-50"
                >
                    Send Us A Message
                </a>
            </div>
        </section>
    </div>
</x-shop::layouts>
