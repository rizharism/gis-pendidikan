import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                    dark:    '#254669', // Navy   – primary buttons, sidebar active, card headers
                    accent:  '#005c83', // Ocean  – hover states, gradients, secondary highlights
                    success: '#27a154', // Emerald – success toasts, Universitas badge
                },
            },
        },
    },

    plugins: [forms],
};

