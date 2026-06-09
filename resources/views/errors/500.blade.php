@extends('errors::minimal')

@extends('errors.layout')

@section('title', '500 — Erreur serveur')

@section('styles')
    <style>
        .accent {
            color: #f87171;
        }

        .glow {
            text-shadow: 0 0 40px rgba(248, 113, 113, 0.3);
        }

        .btn-primary {
            background: rgba(248, 113, 113, 0.12);
            border: 1px solid rgba(248, 113, 113, 0.3);
            color: #fca5a5;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: rgba(248, 113, 113, 0.22);
            border-color: rgba(248, 113, 113, 0.55);
            color: #fecaca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.2);
            color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* Terminal blinking cursor */
        .cursor {
            display: inline-block;
            width: 8px;
            height: 14px;
            background: #f87171;
            animation: blink 1s step-end infinite;
            vertical-align: middle;
            margin-left: 2px;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* Glitch on the 500 bg number */
        .bg-glitch {
            animation: glitch500 5s linear infinite;
        }

        @keyframes glitch500 {

            0%,
            85%,
            100% {
                transform: translate(0);
                filter: none;
            }

            87% {
                transform: translate(-4px, 2px);
                filter: hue-rotate(90deg);
            }

            89% {
                transform: translate(4px, -2px);
                filter: hue-rotate(-90deg);
            }

            91% {
                transform: translate(-2px, 0);
                filter: none;
            }

            93% {
                transform: translate(2px, 1px);
            }

            95% {
                transform: translate(0);
            }
        }

        .stack-line {
            opacity: 0;
            animation: stackReveal 0.3s ease forwards;
        }

        @keyframes stackReveal {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Ghost code background with glitch --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white leading-none tracking-tighter bg-glitch" style="opacity:0.04">500</span>
        </div>

        {{-- Red ambient --}}
        <div class="fixed pointer-events-none" style="top:30%;left:50%;transform:translate(-50%,-50%);width:700px;height:500px;background:radial-gradient(ellipse,rgba(248,113,113,0.06) 0%,transparent 70%)"></div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-xl">

            {{-- Terminal block --}}
            <div class="opacity-0-init animate-fade-up mb-8 mx-auto max-w-md text-left rounded-xl overflow-hidden" style="background:#0d1117;border:1px solid rgba(248,113,113,0.2)">
                {{-- Terminal bar --}}
                <div class="flex items-center gap-2 px-4 py-3" style="background:#161b22;border-bottom:1px solid rgba(248,113,113,0.15)">
                    <div class="w-3 h-3 rounded-full bg-red-500 opacity-80"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500 opacity-80"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500 opacity-80"></div>
                    <span class="ml-2 font-mono text-xs text-slate-500">laravel.log — error</span>
                </div>
                {{-- Terminal content --}}
                <div class="p-4 font-mono text-xs space-y-1" id="terminalLines">
                    <div class="stack-line" style="animation-delay:0.6s;color:#64748b">[{{ now()->format('Y-m-d H:i:s') }}]</div>
                    <div class="stack-line" style="animation-delay:0.8s;color:#f87171">ERROR: Internal Server Error</div>
                    <div class="stack-line" style="animation-delay:1.0s;color:#475569">production.ERROR: An unexpected</div>
                    <div class="stack-line" style="animation-delay:1.2s;color:#475569">exception has occurred.</div>
                    <div class="stack-line flex items-center gap-1" style="animation-delay:1.4s;color:#64748b">
                        <span>$</span><span class="cursor"></span>
                    </div>
                </div>
            </div>

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-mono" style="color:#f87171;background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block animate-pulse"></span>
                ERREUR INTERNE — 500
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                Quelque chose a planté
            </h1>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-10 font-light">
                Une erreur inattendue s'est produite côté serveur.<br>
                L'équipe technique en a été notifiée automatiquement.
            </p>

            {{-- Actions --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="window.location.reload()" class="btn-secondary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Réessayer
                </button>
                <a href="{{ url('/') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Retour à l'accueil
                </a>
            </div>

            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection

