import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import daisyui from 'daisyui';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Lora', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                sage: {
                    50: '#f6f7f5',
                    100: '#e3e7df',
                    200: '#c7cfbf',
                    300: '#a4b196',
                    400: '#849474',
                    500: '#697a59',
                    600: '#526145',
                    700: '#414d38',
                    800: '#363f30',
                    900: '#2f372b',
                },
                earth: {
                    50: '#faf8f5',
                    100: '#f0ebe3',
                    200: '#e0d5c6',
                    300: '#ccb9a1',
                    400: '#b8997c',
                    500: '#aa8465',
                    600: '#9d7358',
                    700: '#835e4a',
                    800: '#6b4e40',
                    900: '#584137',
                },
                cream: {
                    50: '#fefdfb',
                    100: '#fdf9f3',
                    200: '#faf2e6',
                    300: '#f5e6d0',
                    400: '#eed6b4',
                    500: '#e5c49a',
                },
                teal: {
                    50: '#f0fdfb',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                },
                indigo: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                // Theme-aware colors via CSS variables
                'th-primary': 'var(--th-primary)',
                'th-primary-light': 'var(--th-primary-light)',
                'th-primary-dark': 'var(--th-primary-dark)',
                'th-accent': 'var(--th-accent)',
                'th-accent-light': 'var(--th-accent-light)',
                'th-surface': 'var(--th-surface)',
                'th-surface-alt': 'var(--th-surface-alt)',
                'th-bg': 'var(--th-bg)',
                'th-text': 'var(--th-text)',
                'th-text-muted': 'var(--th-text-muted)',
                'th-border': 'var(--th-border)',
            },
            animation: {
                'breathe': 'breathe 6s ease-in-out infinite',
                'float': 'float 6s ease-in-out infinite',
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-up': 'slideUp 0.5s ease-out forwards',
                'slide-in-right': 'slideInRight 0.4s ease-out forwards',
                'pulse-soft': 'pulseSoft 3s ease-in-out infinite',
                'shimmer': 'shimmer 2s linear infinite',
            },
            keyframes: {
                breathe: {
                    '0%, 100%': { transform: 'scale(1)', opacity: '0.8' },
                    '50%': { transform: 'scale(1.08)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '0.6' },
                    '50%': { opacity: '1' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            backdropBlur: {
                xs: '2px',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                'glass-sm': '0 4px 16px 0 rgba(31, 38, 135, 0.05)',
                'inner-soft': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.03)',
                'lift': '0 10px 40px -10px rgba(0, 0, 0, 0.1)',
                'lift-lg': '0 20px 60px -15px rgba(0, 0, 0, 0.12)',
            },
        },
    },

    plugins: [forms, typography, daisyui],

    daisyui: {
        themes: [
            {
                nature: {
                    "primary": "#697a59",
                    "primary-content": "#ffffff",
                    "secondary": "#14b8a6",
                    "secondary-content": "#ffffff",
                    "accent": "#6366f1",
                    "accent-content": "#ffffff",
                    "neutral": "#2f372b",
                    "neutral-content": "#f6f7f5",
                    "base-100": "#fefdfb",
                    "base-200": "#f6f7f5",
                    "base-300": "#e3e7df",
                    "info": "#818cf8",
                    "info-content": "#ffffff",
                    "success": "#14b8a6",
                    "success-content": "#ffffff",
                    "warning": "#e5c49a",
                    "warning-content": "#584137",
                    "error": "#ef4444",
                    "error-content": "#ffffff",
                },
            },
        ],
    },
};
