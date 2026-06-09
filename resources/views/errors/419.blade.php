@extends('errors::minimal')

@extends('errors.layout')

@section('title', '419 — Session expirée')

@section('styles')
    <style>
        .accent {
            color: #fb923c;
        }

        .glow {
            text-shadow: 0 0 40px rgba(251, 146, 60, 0.35);
        }

        .btn-primary {
            background: rgba(251, 146, 60, 0.12);
            border: 1px solid rgba(251, 146, 60, 0.3);
            color: #fdba74;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: rgba(251, 146, 60, 0.22);
            border-color: rgba(251, 146, 60, 0.55);
            color: #fed7aa;
            transform: translateY(-1px);
        }

        .clock-ring {
            stroke-dasharray: 251;
            stroke-dashoffset: 0;
            animation: drainClock 3s cubic-bezier(0.4, 0, 0.6, 1) forwards;
            transform-origin: center;
            transform: rotate(-90deg);
        }

        @keyframes drainClock {
            0% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: 251;
            }
        }

        .timer-blink {
            animation: timerBlink 1.2s ease-in-out infinite;
        }

        @keyframes timerBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        .countdown-bar {
            width: 100%;
            height: 2px;
            background: rgba(251, 146, 60, 0.15);
            border-radius: 999px;
            overflow: hidden;
        }

        .countdown-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #fb923c, #f97316);
            border-radius: 999px;
            animation: drainBar 5s linear forwards;
        }

        @keyframes drainBar {
            0% {
                width: 100%;
            }

            100% {
                width: 0%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Ghost code background --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white leading-none tracking-tighter animate-float-code" style="opacity:0.04">419</span>
        </div>

        {{-- Orange ambient glow --}}
        <div class="fixed pointer-events-none" style="top:30%;left:50%;transform:translate(-50%,-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(251,146,60,0.07) 0%,transparent 70%)"></div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-lg">

            {{-- Animated clock SVG --}}
            <div class="opacity-0-init animate-fade-up mb-8 flex justify-center">
                <div class="relative w-16 h-16">
                    <svg class="w-16 h-16" viewBox="0 0 90 90">
                        <circle cx="45" cy="45" r="40" fill="rgba(251,146,60,0.08)" stroke="rgba(251,146,60,0.2)" stroke-width="1" />
                        <circle cx="45" cy="45" r="40" fill="none" stroke="rgba(251,146,60,0.6)" stroke-width="2" class="clock-ring" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-7 h-7 timer-blink" style="color:#fb923c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-mono" style="color:#fb923c;background:rgba(251,146,60,0.08);border:1px solid rgba(251,146,60,0.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block timer-blink"></span>
                SESSION EXPIRÉE — 419
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                Page expirée
            </h1>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-6 font-light">
                Votre session a expiré ou le formulaire est trop ancien.<br>
                Rechargez la page pour continuer.
            </p>

            {{-- Countdown bar --}}
            <div class="opacity-0-init animate-fade-up-delay2 mb-8 mx-auto max-w-xs">
                <div class="flex justify-between text-xs font-mono text-slate-600 mb-2">
                    <span>token CSRF</span>
                    <span style="color:#fb923c">invalide</span>
                </div>
                <div class="countdown-bar">
                    <div class="countdown-bar-fill" id="countdownBar"></div>
                </div>
            </div>

            {{-- Action principale --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="window.location.reload()" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Recharger la page
                </button>
            </div>

            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection

