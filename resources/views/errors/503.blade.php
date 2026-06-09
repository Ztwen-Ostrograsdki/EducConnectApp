@extends('errors::minimal')

@extends('errors.layout')

@section('title', '503 — Maintenance en cours')

@section('styles')
    <style>
        .accent {
            color: #34d399;
        }

        .glow {
            text-shadow: 0 0 40px rgba(52, 211, 153, 0.3);
        }

        .btn-primary {
            background: rgba(52, 211, 153, 0.12);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: #6ee7b7;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: rgba(52, 211, 153, 0.22);
            border-color: rgba(52, 211, 153, 0.55);
            color: #a7f3d0;
            transform: translateY(-1px);
        }

        /* Progress bar animated */
        .progress-track {
            height: 3px;
            background: rgba(52, 211, 153, 0.12);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #34d399, #10b981);
            animation: progressLoop 2.5s ease-in-out infinite;
            box-shadow: 0 0 8px rgba(52, 211, 153, 0.4);
        }

        @keyframes progressLoop {
            0% {
                width: 0%;
                margin-left: 0%;
            }

            50% {
                width: 60%;
                margin-left: 20%;
            }

            100% {
                width: 0%;
                margin-left: 100%;
            }
        }

        /* Steps */
        .step-done {
            color: #34d399;
        }

        .step-active {
            color: #ffffff;
        }

        .step-pending {
            color: #334155;
        }

        .step-dot-done {
            background: #34d399;
            box-shadow: 0 0 6px rgba(52, 211, 153, 0.5);
        }

        .step-dot-active {
            background: #ffffff;
            animation: dotPulse 1.2s ease-in-out infinite;
        }

        .step-dot-pending {
            background: #1e293b;
            border: 1px solid #334155;
        }

        @keyframes dotPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(255, 255, 255, 0);
            }
        }

        .gear-spin {
            animation: gearSpin 4s linear infinite;
        }

        .gear-spin-rev {
            animation: gearSpin 3s linear infinite reverse;
        }

        @keyframes gearSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Ghost code background --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white leading-none tracking-tighter animate-float-code" style="opacity:0.04">503</span>
        </div>

        {{-- Green ambient glow --}}
        <div class="fixed pointer-events-none" style="top:30%;left:50%;transform:translate(-50%,-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(52,211,153,0.06) 0%,transparent 70%)"></div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-lg">

            {{-- Gears icon --}}
            <div class="opacity-0-init animate-fade-up mb-8 flex justify-center">
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <svg class="w-10 h-10 gear-spin" style="color:#34d399;opacity:0.9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div class="absolute -bottom-1 -right-1">
                        <svg class="w-5 h-5 gear-spin-rev" style="color:#34d399;opacity:0.6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 15.5a3.5 3.5 0 110-7 3.5 3.5 0 010 7zm7.43-1.5a7.5 7.5 0 00.07-1 7.5 7.5 0 00-.07-1l2.16-1.67a.5.5 0 00.12-.64l-2.04-3.53a.5.5 0 00-.61-.22l-2.54 1.02a7.46 7.46 0 00-1.74-1l-.38-2.69A.49.49 0 0014 3h-4a.49.49 0 00-.49.42l-.38 2.69a7.46 7.46 0 00-1.74 1L4.85 6.09a.49.49 0 00-.61.22L2.2 9.84a.49.49 0 00.12.64L4.48 12a7.5 7.5 0 000 2l-2.16 1.67a.5.5 0 00-.12.64l2.04 3.53a.5.5 0 00.61.22l2.54-1.02a7.46 7.46 0 001.74 1l.38 2.69c.07.24.29.42.54.42h4c.25 0 .47-.18.54-.42l.38-2.69a7.46 7.46 0 001.74-1l2.54 1.02a.49.49 0 00.61-.22l2.04-3.53a.5.5 0 00-.12-.64L19.43 14z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-mono" style="color:#34d399;background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                MAINTENANCE — 503
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                En cours de maintenance
            </h1>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-8 font-light">
                La plateforme est temporairement indisponible pour une mise à jour.<br>
                Elle sera de retour dans quelques minutes.
            </p>

            {{-- Progress --}}
            <div class="opacity-0-init animate-fade-up-delay2 mb-8 mx-auto max-w-xs">
                <div class="progress-track mb-3">
                    <div class="progress-fill"></div>
                </div>

                {{-- Steps --}}
                <div class="space-y-2 text-left">
                    @php
                        $steps = [['done', 'Sauvegarde des données'], ['done', 'Déploiement des mises à jour'], ['active', 'Migrations de base de données'], ['pending', 'Redémarrage des services'], ['pending', 'Tests de santé']];
                    @endphp
                    @foreach ($steps as [$status, $label])
                        <div class="flex items-center gap-3 font-mono text-xs step-{{ $status }}">
                            <div class="w-2 h-2 rounded-full flex-shrink-0 step-dot-{{ $status }}"></div>
                            @if ($status === 'done')
                                <span>✓ {{ $label }}</span>
                            @elseif($status === 'active')
                                <span>↻ {{ $label }}…</span>
                            @else
                                <span>○ {{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Retry --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex justify-center">
                <button onclick="window.location.reload()" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Vérifier à nouveau
                </button>
            </div>

            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>
@endsection

