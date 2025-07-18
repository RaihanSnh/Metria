const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                  50: '#f0f9ff',
                  100: '#e0f2fe',
                  200: '#bae6fd',
                  300: '#7dd3fc',
                  400: '#38bdf8',
                  500: '#0ea5e9',
                  600: '#0284c7',
                  700: '#0369a1',
                  800: '#075985',
                  900: '#0c4a6e',
                },
                secondary: {
                  50: '#fdf2f8',
                  100: '#fce7f3',
                  200: '#fbcfe8',
                  300: '#f9a8d4',
                  400: '#f472b6',
                  500: '#ec4899',
                  600: '#db2777',
                  700: '#be185d',
                  800: '#9d174d',
                  900: '#831843',
                },
                accent: {
                  50: '#f7fee7',
                  100: '#ecfccb',
                  200: '#d9f99d',
                  300: '#bef264',
                  400: '#a3e635',
                  500: '#84cc16',
                  600: '#65a30d',
                  700: '#4d7c0f',
                  800: '#365314',
                  900: '#1a2e05',
                },
                neutral: {
                  50: '#fafaf9',
                  100: '#f5f5f4',
                  200: '#e7e5e4',
                  300: '#d6d3d1',
                  400: '#a8a29e',
                  500: '#78716c',
                  600: '#57534e',
                  700: '#44403c',
                  800: '#292524',
                  900: '#1c1917',
                }
              },
              fontFamily: {
                'sans': ['Inter', ...defaultTheme.fontFamily.sans],
                'display': ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
              },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio')
    ],
}; 