@extends('errors::minimal')

@extends('errors.layout')

@section('title', '403 — Accès refusé')

@section('styles')
    <style>
        .accent {
            color: #f87171;
        }

        .glow {
            text-shadow: 0 0 40px rgba(248, 113, 113, 0.35);
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

        .shield-pulse::before,
        .shield-pulse::after {
            content: '';
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            border: 1px solid rgba(248, 113, 113, 0.3);
            animation: shieldRing 2.5s ease-out infinite;
        }

        .shield-pulse::after {
            animation-delay: 1.25s;
        }

        @keyframes shieldRing {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }

            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        .lock-shake {
            animation: lockShake 4s ease-in-out infinite;
        }

        @keyframes lockShake {

            0%,
            80%,
            100% {
                transform: rotate(0deg);
            }

            83% {
                transform: rotate(-8deg);
            }

            86% {
                transform: rotate(8deg);
            }

            89% {
                transform: rotate(-4deg);
            }

            92% {
                transform: rotate(4deg);
            }

            95% {
                transform: rotate(0deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Ghost code background --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white leading-none tracking-tighter animate-float-code" style="opacity:0.04">403</span>
        </div>

        {{-- Red ambient glow --}}
        <div class="fixed pointer-events-none" style="top:30%;left:50%;transform:translate(-50%,-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(248,113,113,0.08) 0%,transparent 70%)"></div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-lg">

            {{-- Icon --}}
            <div class="opacity-0-init animate-fade-up mb-8 flex justify-center">
                <div class="relative">
                    <div class="shield-pulse absolute inset-0 flex items-center justify-center"></div>
                    <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center lock-shake" style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.25)">
                        <svg class="w-7 h-7" style="color:#f87171" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-mono" style="color:#f87171;background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                ACCÈS REFUSÉ — 403
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                Permission requise
            </h1>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-4 font-light">
                Vous n'avez pas les droits nécessaires pour accéder à cette ressource.
            </p>

            @if (isset($exception) && $exception->getMessage())
                <div class="opacity-0-init animate-fade-up-delay2 font-mono text-xs px-4 py-3 rounded-lg mb-8 text-left mx-auto max-w-sm" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15);color:#fca5a5">
                    {{ $exception->getMessage() }}
                </div>
            @else
                <div class="mb-8"></div>
            @endif

            {{-- Actions --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Accueil
                </a>
                <a href="{{ route('login') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Se connecter
                </a>
            </div>

            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection

