export default {
    install(app) {
        app.config.globalProperties.$admin = {
            /**
             * Generates a formatted price.
             *
             * @param {number} price - The price value to be formatted.
             * @returns {string} - The formatted price string.
             */
            formatPrice: (price) => {
                let locale = document.querySelector('meta[http-equiv="content-language"]').content;

                locale = locale.replace(/([a-z]{2})_([A-Z]{2})/g, '$1-$2');

                const currency = JSON.parse(document.querySelector('meta[name="currency"]').content);

                const normalizeSymbol = (rawSymbol = '') => {
                    const trimmed = (rawSymbol || '').trim();
                    const rupeeSymbols = ['₨', '₹', 'रु', 'रू', 'Rs', 'Rs.', 'RS', 'RS.', 'rs', 'rs.'];

                    if (rupeeSymbols.includes(trimmed)) {
                        return 'Rs';
                    }

                    return trimmed;
                };

                const symbol = normalizeSymbol(currency.symbol !== '' ? currency.symbol : currency.code);

                if (! currency.currency_position) {
                    return new Intl.NumberFormat(locale, {
                        style: "currency",
                        currency: currency.code,
                    }).format(price);
                }

                const formatter = new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency.code,
                    minimumFractionDigits: currency.decimal ?? 2
                });

                const formattedCurrency = formatter.formatToParts(price)
                    .map(part => {
                        switch (part.type) {
                            case 'currency':
                                return '';

                            case 'group':
                                return currency.group_separator === ''
                                    ? part.value
                                    : currency.group_separator;

                            case 'decimal':
                                return currency.decimal_separator === ''
                                    ? part.value
                                    : currency.decimal_separator;

                            default:
                                return part.value;
                        }
                    })
                    .join('');

                let position = currency.currency_position;

                if (symbol && symbol.toLowerCase().startsWith('rs')) {
                    if (['right', 'right_with_space'].includes(position)) {
                        position = 'left_with_space';
                    } else if (position === 'left') {
                        position = 'left_with_space';
                    }
                }

                switch (position) {
                    case 'left':
                        return symbol + formattedCurrency;

                    case 'left_with_space':
                        return symbol + ' ' + formattedCurrency;

                    case 'right':
                        return formattedCurrency + symbol;

                    case 'right_with_space':
                        return formattedCurrency + ' ' + symbol;

                    default:
                        return formattedCurrency;
                }
            },
        };
    },
};
