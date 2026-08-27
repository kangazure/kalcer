/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{ts,js}',
        './resources/scss/**/*.scss',
    ],
    safelist: [
        'aos-init',
        'aos-animate',
    ],
    theme: {
        extend: {
            colors: {
                // Dikalibrasi presisi dari logo resmi PT JTS (sampling pixel):
                // oranye #F98601, merah #FB070D, navy #191DB7
                brand: {
                    DEFAULT: '#fa8600',
                    50: '#fff6eb',
                    100: '#ffead1',
                    200: '#ffd4a4',
                    300: '#feb867',
                    400: '#fea034',
                    500: '#fa8600',
                    600: '#d17000',
                    700: '#a85a00',
                    800: '#804400',
                    900: '#572f00',
                    950: '#381e00',
                },
                'brand-red': {
                    DEFAULT: '#ff0309',
                    50: '#ffebeb',
                    100: '#fed2d3',
                    200: '#fea5a7',
                    300: '#fd686c',
                    400: '#fc363b',
                    500: '#ff0309',
                    600: '#d90005',
                    700: '#b00004',
                    800: '#880003',
                    900: '#5f0002',
                    950: '#400002',
                },
                'brand-navy': {
                    DEFAULT: '#1418bc',
                    50: '#ededfd',
                    100: '#d7d7f9',
                    200: '#aeb0f4',
                    300: '#787bed',
                    400: '#4c4fe6',
                    500: '#1418bc',
                    600: '#101397',
                    700: '#0c0f72',
                    800: '#080a4d',
                    900: '#040529',
                    950: '#01020d',
                },
                ink: {
                    DEFAULT: '#0a0a0a',
                    50: '#f7f7f7',
                    100: '#e3e3e3',
                    200: '#c8c8c8',
                    300: '#a4a4a4',
                    400: '#818181',
                    500: '#666666',
                    600: '#515151',
                    700: '#434343',
                    800: '#262626',
                    900: '#161616',
                    950: '#0a0a0a',
                },
                surface: {
                    light: '#ffffff',
                    soft: '#f5f5f7',
                    dark: '#0d0d0f',
                    darksoft: '#15151a',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                display: ['"Clash Display"', '"Plus Jakarta Sans"', 'sans-serif'],
                mono: ['"JetBrains Mono"', 'monospace'],
            },
            backgroundImage: {
                'grid-pattern': 'linear-gradient(rgba(250,134,0,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(250,134,0,0.08) 1px, transparent 1px)',
                'radial-glow': 'radial-gradient(circle at center, rgba(250,134,0,0.25), transparent 70%)',
                'hero-gradient': 'linear-gradient(135deg, #0a0a0a 0%, #1f0d05 45%, #381e00 100%)',
                // Gradient utama logo: merah di satu ujung, oranye di ujung lain —
                // dipakai untuk headline/teks aksen agar konsisten dengan brand mark.
                'brand-gradient': 'linear-gradient(90deg, #ff0309 0%, #fa8600 100%)',
                'brand-gradient-diagonal': 'linear-gradient(135deg, #ff0309 0%, #fa8600 60%, #fea034 100%)',
            },
            backdropBlur: {
                xs: '2px',
            },
            boxShadow: {
                glow: '0 0 40px rgba(250,134,0,0.35)',
                'glow-lg': '0 0 80px rgba(250,134,0,0.45)',
                'glow-red': '0 0 40px rgba(255,3,9,0.3)',
                glass: '0 8px 32px rgba(0,0,0,0.25)',
                'inner-glass': 'inset 0 1px 0 rgba(255,255,255,0.08)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                'gradient-shift': 'gradientShift 8s ease infinite',
                'marquee': 'marquee 30s linear infinite',
                'fade-up': 'fadeUp 0.8s ease forwards',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-18px)' },
                },
                pulseGlow: {
                    '0%, 100%': { opacity: 0.4, transform: 'scale(1)' },
                    '50%': { opacity: 0.9, transform: 'scale(1.05)' },
                },
                gradientShift: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                fadeUp: {
                    '0%': { opacity: 0, transform: 'translateY(40px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
            },
            backdropSaturate: {
                150: '1.5',
            },
            transitionTimingFunction: {
                'expo-out': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
