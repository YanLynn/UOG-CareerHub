import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['selector', '[class="p-dark"]'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            typography: {
                DEFAULT: {
                  css: {
                    'ol > li::marker': {
                      color: '#3b82f6', // Tailwind blue-500
                    },
                    'ul > li::marker': {
                      color: '#ef4444', // red-500
                    },
                  },
                },
              },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('tailwindcss-primeui'),
        require('@tailwindcss/typography')
    ],
    mode: 'jit',
};
