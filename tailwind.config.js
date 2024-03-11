import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            boxShadow: {
                'l': '0 0 56px 0 rgba(0,0,0,0.04)',
                'xl': '0 0 32px 0 rgba(0,0,0,0.08)',
                '2xl':'0 0 56px rgba(0, 0, 0, 0.12)',
                '3xl': '0 6px 78px rgba(0, 0, 0, 0.08)',
                '4xl':  '0 6px 78px rgba(0, 0, 0, 0.24)',
                '5xl': '0 -4px 48px rgba(0, 0, 0, 0.08)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'actionBtn': {
                    'primary': '#4C1D95',
                    'primaryHover': '#360F73',
                },
                'btn': {
                    'primary': '#ff7400',
                    'primaryHover': '#ff4d00',
                    'secondary': '#EDE9FE',
                    'secondaryHover': '#DBCFFF',
                    'textPrimary': '#FFFFFF',
                    'textSecondary': '#4C1D95'
                },
                'inputBox': {
                    'background': '#FFFFFF',
                    'border': '#A78BFA',
                    'fill': '#ff7400',
                },
                'general': {
                    'error': '#B91C1C'
                },
                'modal': {
                    'contentBackground': '#FFFFFF',
                    'tint': '#000C',
                    'topBarTextColor': '#FFFFFF',
                    'topBarBackground': '#000000',

                },
                'textfield': {
                    'border': '#E5E7EB',
                    'inputText': '#0E051B',
                    'placeholder': '#6B7280'
                },
                'black': '#000000',
                'dark_grey': '#D3D3D3',
                'light_grey': '#f7f6f2',
                'main': '#ff7400',
                'main_hover': '#ff4d00',
                'tint': '#00000099',
                'white': '#FFFFFF',
            },
            screens: {
                'md': '768px',
                // => @media (min-width: 768px) { ... }

                'lg': '1024px',
                // => @media (min-width: 1024px) { ... }

                'xl': '1366px',
                // => @media (min-width: 1280px) { ... }

                'xxl': '1920px',
                // => @media (min-width: 1536px) { ... }
            }
        },
    },

    plugins: [forms],
};
