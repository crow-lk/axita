<x-shop::layouts>
    <x-slot:title>
        Contact Us - Axita Computers
    </x-slot>

    <div class="mx-auto w-full max-w-[1400px] space-y-10 px-4 py-10 sm:px-6 lg:px-8 max-sm:px-2">
        <!-- Hero -->
        <section class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl sm:p-10 lg:p-12">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/60">
                        We respond within one business hour
                    </p>

                    <h1 class="mt-4 text-4xl font-bold leading-tight lg:text-5xl">
                        Reach the Axita team for repairs, orders, or project support
                    </h1>

                    <p class="mt-4 text-base text-white/80">
                        Call, message, or visit our Beligaha Junction store. The same service desk handles retail, repairs,
                        and on-site bookings so you get a single update trail.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            href="tel:+94771284323"
                            class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                        >
                            Call +94 77 128 4323
                        </a>

                        <a
                            href="https://wa.me/94771284323?text=Hello%20Axita%2C%20I%20need%20help%20with..."
                            target="_blank"
                            class="inline-flex items-center rounded-full border border-white/30 px-6 py-3 font-semibold text-white/90 transition hover:bg-white/10"
                        >
                            Message on WhatsApp
                        </a>
                    </div>
                </div>

                <div class="flex flex-1 flex-wrap gap-4">
        @php
            $contactStats = [
                ['label' => 'Store hours', 'value' => 'Mon–Sat · 9AM–7PM · Sun · 9AM–6PM'],
                ['label' => 'Service queue', 'value' => 'Walk-in & courier pickups'],
                ['label' => 'Response time', 'value' => '< 60 mins'],
            ];
                    @endphp

                    @foreach ($contactStats as $stat)
                        <div class="min-w-[180px] flex-1 rounded-2xl border border-white/15 bg-white/10 p-5 text-center">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">{{ $stat['label'] }}</p>
                            <p class="mt-3 text-xl font-semibold">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact channels -->
        @php
            $channels = [
            [
                'title' => 'Call the service desk',
                'detail' => '+94 77 128 4323',
                'helper' => 'Mon – Sat, 9:00 AM to 7:00 PM · Sun till 6:00 PM',
                'cta' => 'Call now',
                'href' => 'tel:+94771284323',
                'color' => 'from-green-500 to-emerald-600',
                ],
                [
                    'title' => 'Email support & billing',
                    'detail' => 'info@axita.lk',
                    'helper' => 'We reply within 24 hours with a ticket ID',
                    'cta' => 'Send email',
                    'href' => 'mailto:info@axita.lk',
                    'color' => 'from-blue-500 to-indigo-600',
                ],
                [
                    'title' => 'Visit the showroom & lab',
                    'detail' => 'Beligaha Junction, Galle',
                    'helper' => 'Ground floor retail + upstairs diagnostics lab',
                    'cta' => 'Get directions',
                    'href' => 'https://maps.google.com/?q=Beligaha+Junction+Galle',
                    'color' => 'from-orange-500 to-rose-600',
                ],
            ];
        @endphp

        <section class="grid gap-6 md:grid-cols-3">
            @foreach ($channels as $channel)
                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-r {{ $channel['color'] }} text-white shadow">
                        <span class="text-lg font-semibold">{{ \Illuminate\Support\Str::of($channel['title'])->substr(0, 2)->upper() }}</span>
                    </div>

                    <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ $channel['title'] }}</h3>
                    <p class="mt-2 text-lg font-semibold text-slate-800">{{ $channel['detail'] }}</p>
                    <p class="text-sm text-slate-500">{{ $channel['helper'] }}</p>

                    <a
                        href="{{ $channel['href'] }}"
                        class="mt-4 inline-flex items-center text-sm font-semibold text-orange-600 hover:text-orange-500"
                        target="{{ \Illuminate\Support\Str::startsWith($channel['href'], 'http') ? '_blank' : '_self' }}"
                    >
                        {{ $channel['cta'] }}
                        <span class="ml-1 text-base">→</span>
                    </a>
                </div>
            @endforeach
        </section>

        <!-- Form + Support info -->
        <section class="grid gap-8 rounded-3xl border border-slate-100 bg-white p-8 shadow-sm sm:p-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Send us the details</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Share as much information as you can about the device, order, or project. Photos and serial numbers help us respond faster.
                </p>

                <x-shop::form :action="route('shop.home.contact_us.send_mail')" class="mt-6 space-y-5">
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required text-sm font-semibold text-slate-700">
                            @lang('shop::app.home.contact.name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            placeholder="Your full name"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required text-sm font-semibold text-slate-700">
                            @lang('shop::app.home.contact.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            placeholder="name@example.com"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="text-sm font-semibold text-slate-700">
                            @lang('shop::app.home.contact.phone-number')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            name="contact"
                            :value="old('contact')"
                            placeholder="+94 77 123 4567"
                        />

                        <x-shop::form.control-group.error control-name="contact" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required text-sm font-semibold text-slate-700">
                            @lang('shop::app.home.contact.desc')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="textarea"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            name="message"
                            rules="required"
                            placeholder="Tell us about the repair, order, or request..."
                            rows="5"
                        />

                        <x-shop::form.control-group.error control-name="message" />
                    </x-shop::form.control-group>

                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div>
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-orange-500 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-orange-400"
                    >
                        @lang('shop::app.home.contact.submit')
                    </button>
                </x-shop::form>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-slate-500">Support coverage</p>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">What to include</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="text-green-500">✓</span>
                            Store order number, invoice, or device serial.
                        </li>
                        <li class="flex gap-3">
                            <span class="text-green-500">✓</span>
                            Photos/video of the issue, or screenshots of error messages.
                        </li>
                        <li class="flex gap-3">
                            <span class="text-green-500">✓</span>
                            A preferred contact window or pickup location.
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-100 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-orange-500">Primary contacts</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div>
                            <p class="font-semibold text-slate-900">Main phone</p>
                            <p>+94 77 128 4323</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Email</p>
                            <p>info@axita.lk</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Store & lab</p>
                            <p>Beligaha Junction, Galle, Sri Lanka</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional info -->
        <section class="rounded-3xl border border-slate-100 bg-slate-50 p-8 shadow-sm sm:p-10">
            <div class="grid gap-8 lg:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Visit the store</p>
                    <h2 class="mt-3 text-2xl font-bold text-slate-900">Axita Computers · Beligaha Junction</h2>
                    <p class="mt-3 text-sm text-slate-600">
                        We are a 5-minute drive from central Galle and minutes away from the Southern Expressway exit.
                        Parking is available behind the building, and courier pickups happen twice daily.
                    </p>

                    <div class="mt-6 grid gap-4 text-sm text-slate-700 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white bg-white p-4 shadow">
                            <p class="font-semibold text-slate-900">Store & lab hours</p>
                            <p>Monday – Saturday</p>
                            <p>9:00 AM – 7:00 PM</p>
                            <p class="mt-2">Sunday</p>
                            <p>9:00 AM – 6:00 PM</p>
                            <p class="text-xs text-slate-500">Closed on Poya days</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white p-4 shadow">
                            <p class="font-semibold text-slate-900">Courier drop-offs</p>
                            <p>Monday – Friday</p>
                            <p>1:00 PM & 5:00 PM dispatch slots</p>
                            <p class="text-xs text-slate-500">Tracking shared via SMS + email</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white bg-white p-6 shadow">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-slate-500">FAQ highlights</p>
                    <ul class="mt-4 space-y-4 text-sm text-slate-700">
                        <li>
                            <p class="font-semibold text-slate-900">Do you ship island-wide?</p>
                            <p>Yes. We partner with trusted couriers and provide insurance for high-value shipments.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">Can I track my repair?</p>
                            <p>Use the repair-status link in the header or reply to your ticket email for the latest update.</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-900">Do you handle business projects?</p>
                            <p>We scope CCTV, Wi-Fi, POS, and managed IT contracts and can schedule site visits across the Southern Province.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50 via-white to-white p-8 text-center shadow-[0_20px_50px_rgba(8,15,23,0.06)] sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-orange-500">Still unsure?</p>
            <h2 class="mt-4 text-3xl font-bold text-slate-900">Send us photos or invoices and we’ll recommend the next step</h2>
            <p class="mt-3 text-base text-slate-600">
                Attach files to info@axita.lk or WhatsApp +94 77 128 4323. We will review and share a quote or troubleshooting plan.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a
                    href="{{ route('shop.home.contact_us') }}#"
                    class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-orange-400"
                    onclick="document.querySelector('form[action=\'{{ route('shop.home.contact_us.send_mail') }}\']')?.scrollIntoView({ behavior: 'smooth' });"
                >
                    Use the contact form
                </a>
                <a
                    href="mailto:info@axita.lk?subject=Axita%20Support%20Request"
                    class="inline-flex items-center rounded-full border border-orange-200 px-6 py-3 font-semibold text-orange-600 transition hover:bg-orange-50"
                >
                    Email our team directly
                </a>
            </div>
        </section>
        </div>
    </div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>
