<x-shop::layouts>
    <x-slot:title>
        Services - Axita Computers
    </x-slot>

    <div class="mx-auto w-full max-w-[1400px] space-y-10 px-4 py-10 sm:px-6 lg:px-8">
            <!-- Hero -->
            <section class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl sm:p-10 lg:p-12">
                <div class="flex flex-col gap-10 lg:flex-row lg:items-center">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/70">
                            IT Shop & Repair Center · Galle
                        </p>

                        <h1 class="mt-4 text-4xl font-bold leading-tight lg:text-5xl">
                            Fast, honest computer service for home, gaming, and business customers
                        </h1>

                        <p class="mt-4 text-base text-white/80">
                            From laptop repairs to POS deployments, our in-store technicians use genuine parts, board-level diagnostics,
                            and same-day updates so you always know the status of your device.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                href="{{ route('shop.home.contact_us') }}"
                                class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                            >
                                Book a Repair Slot
                                <span class="ml-2 text-2xl">→</span>
                            </a>

                            <div class="flex items-center gap-4 rounded-2xl border border-white/30 px-5 py-3 text-sm text-white/80">
                                <div class="text-3xl">⚡</div>
                                <div>
                                    <p class="font-semibold text-white">Express diagnostics</p>
                                    <p>Updates in under 30 minutes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-wrap gap-4">
                        @php
                            $stats = [
                                ['label' => 'Avg. repair time', 'value' => '48 hrs'],
                                ['label' => 'Devices serviced / month', 'value' => '300+'],
                                ['label' => 'Business sites supported', 'value' => '120'],
                            ];
                        @endphp

                        @foreach ($stats as $stat)
                            <div class="min-w-[160px] flex-1 rounded-2xl border border-white/15 bg-white/10 p-5 text-center">
                                <p class="text-xs uppercase tracking-[0.3em] text-white/70">{{ $stat['label'] }}</p>
                                <p class="mt-3 text-3xl font-bold">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Service categories -->
            <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm sm:p-10">
                <div class="mb-10 text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">What we fix</p>
                    <h2 class="mt-4 text-3xl font-bold text-slate-900">Essential services for modern devices</h2>
                    <p class="mt-4 text-base text-slate-600">
                        Whether you purchased from us or not, every ticket follows the same transparent checklist and documentation.
                    </p>
                </div>

                @php
                    $services = [
                        ['title' => 'Laptop & desktop repairs', 'copy' => 'Motherboard rework, liquid damage cleaning, SSD upgrades, hinge rebuilds, and thermal servicing.'],
                        ['title' => 'Custom PC builds & upgrades', 'copy' => 'Gaming rigs, rendering workstations, and smart office PCs with validated component compatibility.'],
                        ['title' => 'Data recovery & OS services', 'copy' => 'Cloning, RAID rebuilds, virus cleanup, OS installation, and licensed software imaging.'],
                        ['title' => 'Store & office IT support', 'copy' => 'POS terminals, CCTV, structured cabling, Wi-Fi coverage, and preventative maintenance visits.'],
                        ['title' => 'Peripheral & accessory care', 'copy' => 'Printer servicing, monitor calibration, UPS battery swaps, and smartphone/tablet repair.'],
                        ['title' => 'On-site troubleshooting', 'copy' => 'Technicians travel across Galle, Matara, and Hambantota with loaner equipment when required.'],
                    ];
                @endphp

                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($services as $service)
                        <div class="rounded-2xl border border-slate-100/70 p-6 transition hover:-translate-y-1 hover:border-orange-200 hover:shadow-lg">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl text-orange-500">◆</span>
                                <h3 class="text-xl font-semibold text-slate-900">{{ $service['title'] }}</h3>
                            </div>
                            <p class="mt-4 text-base text-slate-600">
                                {{ $service['copy'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Why choose us -->
            <section class="grid gap-8 rounded-3xl border border-slate-100 bg-slate-50 p-8 shadow-sm sm:p-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Why it matters</p>
                    <h2 class="mt-4 text-3xl font-bold text-slate-900">
                        Built for people who can’t afford downtime
                    </h2>
                    <p class="mt-4 text-base text-slate-600">
                        Every ticket is logged into our service desk so you can track parts, approvals, and delivery estimates in one place.
                        Most fixes are completed in-house to maintain quality control and protect your data.
                    </p>

                    <ul class="mt-6 space-y-4 text-slate-700">
                        <li class="flex gap-3">
                            <span class="text-green-500">✔</span>
                            Genuine components with manufacturer-backed warranties.
                        </li>
                        <li class="flex gap-3">
                            <span class="text-green-500">✔</span>
                            Board-level tools, BGA rework, and calibrated diagnostics benches.
                        </li>
                        <li class="flex gap-3">
                            <span class="text-green-500">✔</span>
                            Loaner laptops and routers available for priority business calls.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-white bg-white p-8 shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">How a typical repair works</h3>
                    <ol class="mt-6 space-y-5 text-slate-700">
                        <li>
                            <p class="font-semibold text-slate-900">1. Intake & diagnostics</p>
                            <p class="text-sm text-slate-600">We log the issue, capture photos, and run hardware tests within 30 minutes.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">2. Estimate approval</p>
                            <p class="text-sm text-slate-600">You receive a clear breakdown of parts, labour, and timelines before we proceed.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">3. Repair & quality check</p>
                            <p class="text-sm text-slate-600">Certified technicians complete the work, update firmware, and stress-test the device.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">4. Collection or delivery</p>
                            <p class="text-sm text-slate-600">We package the device safely or arrange courier drop-off with documentation.</p>
                        </li>
                    </ol>
                </div>
            </section>

            <!-- CTA -->
            <section class="rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50 via-white to-white p-8 text-center shadow-[0_20px_50px_rgba(8,15,23,0.06)] sm:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Need help fast?</p>
                <h2 class="mt-4 text-3xl font-bold text-slate-900">Visit our service counter or schedule a pickup</h2>
                <p class="mt-4 text-base text-slate-600">
                    Call +94 77 128 4323 or message us on WhatsApp to secure the next available slot.
                    We respond to most enquiries in under one business hour.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a
                        href="tel:+94771284323"
                        class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                    >
                        Call the Service Desk
                    </a>
                    <a
                        href="{{ route('shop.home.contact_us') }}"
                        class="inline-flex items-center rounded-full border border-orange-200 px-6 py-3 font-semibold text-orange-600 transition hover:bg-orange-50"
                    >
                        Send Repair Details
                    </a>
                </div>
            </section>
    </div>
</x-shop::layouts>
