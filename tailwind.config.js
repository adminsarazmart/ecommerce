/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f0f4ff',
                    100: '#dbe4ff',
                    200: '#bac8ff',
                    300: '#91a7ff',
                    400: '#748ffc',
                    500: '#5c7cfa',
                    600: '#4c6ef5',
                    700: '#4263eb',
                    800: '#3b5bdb',
                    900: '#364fc7',
                    950: '#2b3fa0',
                },
                luxury: {
                    gold: '#d4a853',
                    'gold-light': '#f0d78a',
                    'gold-dark': '#b8922e',
                    cream: '#faf6f0',
                    charcoal: '#1a1a2e',
                    navy: '#16213e',
                    midnight: '#0f3460',
                },
                glass: {
                    light: 'rgba(255, 255, 255, 0.25)',
                    dark: 'rgba(0, 0, 0, 0.25)',
                    border: 'rgba(255, 255, 255, 0.18)',
                    'border-dark': 'rgba(0, 0, 0, 0.12)',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                heading: ['Playfair Display', 'Georgia', 'serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
            backdropBlur: {
                xs: '2px',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'fade-in-down': 'fadeInDown 0.6s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
                'slide-in-left': 'slideInLeft 0.3s ease-out',
                'slide-in-up': 'slideInUp 0.4s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'spin-slow': 'spin 3s linear infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                'shimmer': 'shimmer 2s linear infinite',
                'float': 'float 3s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite alternate',
                'countdown': 'countdown 1s linear forwards',
                'slide-down': 'slideDown 0.3s ease-out',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideInLeft: {
                    '0%': { transform: 'translateX(-100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideInUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                bounceGentle: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glow: {
                    '0%': { boxShadow: '0 0 5px rgba(92, 124, 250, 0.5)' },
                    '100%': { boxShadow: '0 0 20px rgba(92, 124, 250, 0.8)' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-100%)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'glass-gradient': 'linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05))',
                'glass-gradient-dark': 'linear-gradient(135deg, rgba(0,0,0,0.2), rgba(0,0,0,0.1))',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.15)',
                'glass-lg': '0 8px 32px 0 rgba(31, 38, 135, 0.25)',
                'glass-sm': '0 2px 8px 0 rgba(31, 38, 135, 0.10)',
                'neon': '0 0 10px rgba(92, 124, 250, 0.5), 0 0 20px rgba(92, 124, 250, 0.3)',
                'inner-glow': 'inset 0 0 20px rgba(255,255,255,0.05)',
            },
        },
    },
    plugins: [],
};
