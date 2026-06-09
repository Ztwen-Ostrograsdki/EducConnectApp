<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Erreur') — EducConnect</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        mono: ['"DM Mono"', 'monospace'],
                        serif: ['"Instrument Serif"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float-slow': 'float 8s ease-in-out infinite',
                        'float-code': 'floatCode 6s ease-in-out infinite',
                        'fade-up': 'fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'fade-up-delay': 'fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards',
                        'fade-up-delay2': 'fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards',
                        'fade-up-delay3': 'fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.45s forwards',
                        'pulse-ring': 'pulseRing 2.5s ease-out infinite',
                        'glitch': 'glitch 3s linear infinite',
                        'scan': 'scan 3s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)'
                            },
                            '33%': {
                                transform: 'translateY(-12px) rotate(0.5deg)'
                            },
                            '66%': {
                                transform: 'translateY(6px) rotate(-0.3deg)'
                            },
                        },
                        floatCode: {
                            '0%, 100%': {
                                transform: 'translateY(0px)',
                                opacity: '0.04'
                            },
                            '50%': {
                                transform: 'translateY(-20px)',
                                opacity: '0.07'
                            },
                        },
                        fadeUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0px)'
                            },
                        },
                        pulseRing: {
                            '0%': {
                                transform: 'scale(1)',
                                opacity: '0.6'
                            },
                            '100%': {
                                transform: 'scale(2.2)',
                                opacity: '0'
                            },
                        },
                        glitch: {
                            '0%, 90%, 100%': {
                                transform: 'translate(0)'
                            },
                            '92%': {
                                transform: 'translate(-3px, 1px)'
                            },
                            '94%': {
                                transform: 'translate(3px, -1px)'
                            },
                            '96%': {
                                transform: 'translate(-1px, 2px)'
                            },
                        },
                        scan: {
                            '0%': {
                                transform: 'translateY(-100%)'
                            },
                            '100%': {
                                transform: 'translateY(100vh)'
                            },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .opacity-0-init {
            opacity: 0;
        }

        .noise-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    @yield('styles')
</head>

<body class="@yield('body-class', 'bg-slate-950') min-h-screen noise-bg overflow-hidden">

    @yield('content')

    @yield('scripts')
</body>

</html>
