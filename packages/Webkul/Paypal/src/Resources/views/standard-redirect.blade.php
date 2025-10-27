<?php $paypalStandard = app('Webkul\Paypal\Payment\Standard') ?>

<body>
    You will be redirected to the PayHere payment gateway in a few seconds.

    <form action="{{ $paypalStandard->getConfigData('sandbox') ? 'https://sandbox.payhere.lk/pay/checkout' : 'https://www.payhere.lk/pay/checkout' }}" id="paypal_standard_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">

        @foreach ($paypalStandard->getFormFields() as $name => $value)
            <input
                type="hidden"
                name="{{ $name }}"
                value="{{ $value }}"
            />
        @endforeach
    </form>

    <script type="text/javascript">
        document.getElementById("paypal_standard_checkout").submit();
    </script>
</body>
