@php
    $normalizePrice = static function (?string $value): string {
        if (! $value) {
            return '';
        }

        $value = str_replace("\u{00A0}", ' ', $value);

        $value = preg_replace_callback('/(₨|₹|रु|रू|Rs\.?|RS\.?|rs\.?)(?:\s*)(?=\d)/u', function ($matches) {
            return 'Rs ';
        }, $value);

        $value = preg_replace_callback('/(\d[\d.,]*)(?:\s*)(₨|₹|रु|रू|Rs\.?|RS\.?|rs\.?)/u', function ($matches) {
            return 'Rs '.$matches[1];
        }, $value);

        $value = preg_replace('/Rs\s+/u', 'Rs ', $value);
        $value = preg_replace('/\s{2,}/', ' ', $value);

        return trim($value);
    };

    $regularPrice = $normalizePrice($prices['regular']['formatted_price'] ?? '');
    $finalPrice = $normalizePrice($prices['final']['formatted_price'] ?? '');
@endphp

@if ($prices['final']['price'] < $prices['regular']['price'])
    <p
        class="final-price font-medium text-zinc-500 line-through max-sm:leading-4"
        aria-label="{{ $regularPrice }}"
    >
        {{ $regularPrice }}
    </p>

    <p class="font-semibold max-sm:leading-4">
        {{ $finalPrice }}
    </p>
@else
    <p class="final-price font-semibold max-sm:leading-4">
        {{ $regularPrice }}
    </p>
@endif
