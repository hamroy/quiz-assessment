import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eef6ff',
                    100: '#d9ebff',
                    200: '#bcdbff',
                    300: '#8ec4ff',
                    400: '#59a2ff',
                    500: '#337dff',
                    600: '#1f5ef5',
                    700: '#1849e1',
                    800: '#1a3cb6',
                    900: '#1b388f',
                    950: '#152357',
                },
            },
        },
    },

    plugins: [forms],
};
