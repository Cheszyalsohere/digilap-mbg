import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#5C8B6E',
                    dark: '#4A7259',
                    light: '#EBF2ED',
                },
                accent: {
                    DEFAULT: '#D4A853',
                    light: '#FDF3DC',
                },
                bg: '#FAFAF7',
                surface: '#F0EDE6',
                bordered: '#E2DDD6',
                ink: '#2C2C2A',
                muted: '#7A7670',
                danger: '#C0392B',
                success: '#27AE60',
                warning: '#D4A853',
            },
            borderRadius: {
                xl: '12px',
                '2xl': '16px',
            },
            boxShadow: {
                soft: '0 1px 4px rgba(0,0,0,0.06)',
                card: '0 2px 8px rgba(0,0,0,0.05)',
            },
        },
    },
    plugins: [],
};
