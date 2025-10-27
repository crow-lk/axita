/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1440px",
            },

            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        fontSize: {
            'xs': ['0.65rem', { lineHeight: '1rem' }],      // Reduced from 0.75rem
            'sm': ['0.76rem', { lineHeight: '1.25rem' }],   // Reduced from 0.875rem
            'base': ['0.875rem', { lineHeight: '1.5rem' }], // Reduced from 1rem
            'lg': ['0.98rem', { lineHeight: '1.75rem' }],   // Reduced from 1.125rem
            'xl': ['1.09rem', { lineHeight: '1.75rem' }],   // Reduced from 1.25rem
            '2xl': ['1.31rem', { lineHeight: '2rem' }],     // Reduced from 1.5rem
            '3xl': ['1.64rem', { lineHeight: '2.25rem' }],  // Reduced from 1.875rem
            '4xl': ['1.97rem', { lineHeight: '2.5rem' }],   // Reduced from 2.25rem
            '5xl': ['2.62rem', { lineHeight: '1' }],        // Reduced from 3rem
            '6xl': ['3.28rem', { lineHeight: '1' }],        // Reduced from 3.75rem
            '7xl': ['4.16rem', { lineHeight: '1' }],        // Reduced from 4.5rem
            '8xl': ['5.25rem', { lineHeight: '1' }],        // Reduced from 6rem
            '9xl': ['7.00rem', { lineHeight: '1' }],        // Reduced from 8rem
        },

        extend: {
            colors: {
                navyBlue: "#e85805",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
            },

            fontFamily: {
                poppins: ["Poppins"],
                dmserif: ["DM Serif Display"],
            },
        }
    },

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
