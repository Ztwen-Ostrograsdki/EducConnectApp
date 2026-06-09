@extends('errors::minimal')

@extends('errors.layout')

@section('title', '404 — Page introuvable')

@section('styles')
    <style>
        .accent {
            color: #818cf8;
        }

        .accent-bg {
            background-color: #818cf8;
        }

        .glow {
            text-shadow: 0 0 40px rgba(129, 140, 248, 0.4);
        }

        .border-accent {
            border-color: rgba(129, 140, 248, 0.3);
        }

        .btn-primary {
            background: rgba(129, 140, 248, 0.15);
            border: 1px solid rgba(129, 140, 248, 0.35);
            color: #a5b4fc;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: rgba(129, 140, 248, 0.25);
            border-color: rgba(129, 140, 248, 0.6);
            color: #c7d2fe;
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

        .orbit {
            border: 1px dashed rgba(129, 140, 248, 0.15);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: spin linear infinite;
        }

        @keyframes spin {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #818cf8;
            position: absolute;
            top: -3px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 8px rgba(129, 140, 248, 0.8);
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Scanline effect --}}
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-[0.015]">
            <div class="w-full h-1 bg-white animate-scan"></div>
        </div>

        {{-- Large ghost code --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white animate-float-code leading-none tracking-tighter" style="opacity:0.04">404</span>
        </div>

        {{-- Orbital rings --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none">
            <div class="relative w-[500px] h-[500px] opacity-30">
                <div class="orbit" style="width:300px;height:300px;animation-duration:20s;">
                    <div class="dot"></div>
                </div>
                <div class="orbit" style="width:420px;height:420px;animation-duration:35s;animation-direction:reverse;border-style:dotted;">
                    <div class="dot" style="background:#c7d2fe;top:auto;bottom:-3px;box-shadow:0 0 8px rgba(199,210,254,0.6);"></div>
                </div>
            </div>
        </div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-lg">

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-8 inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-accent text-xs font-mono" style="color:#818cf8;background:rgba(129,140,248,0.08)">
                <span class="w-1.5 h-1.5 rounded-full accent-bg animate-pulse inline-block"></span>
                ERREUR 404
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                Page introuvable
            </h1>

            {{-- Code error styled --}}
            <div class="opacity-0-init animate-fade-up-delay font-mono text-sm mb-6 px-4 py-3 rounded-lg mx-auto max-w-sm" style="background:rgba(129,140,248,0.06);border:1px solid rgba(129,140,248,0.15);color:#94a3b8">
                <span style="color:#818cf8">GET</span> <span class="text-slate-500">{{ request()->path() !== '/' ? '/' . request()->path() : '/' }}</span>
                <span class="text-slate-600 mx-2">→</span>
                <span style="color:#f87171">404 Not Found</span>
            </div>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-10 font-light">
                Cette page n'existe pas ou a été déplacée.<br>
                Vérifiez l'adresse ou retournez à l'accueil.
            </p>

            {{-- Actions --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
                <a href="{{ url('/') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Accueil
                </a>
            </div>

            {{-- Footer --}}
            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection

