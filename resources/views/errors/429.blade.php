@extends('errors::minimal')

@extends('errors.layout')

@section('title', '429 — Trop de requêtes')

@section('styles')
    <style>
        .accent {
            color: #a78bfa;
        }

        .glow {
            text-shadow: 0 0 40px rgba(167, 139, 250, 0.35);
        }

        .btn-primary {
            background: rgba(167, 139, 250, 0.12);
            border: 1px solid rgba(167, 139, 250, 0.3);
            color: #c4b5fd;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: rgba(167, 139, 250, 0.22);
            border-color: rgba(167, 139, 250, 0.55);
            color: #ddd6fe;
            transform: translateY(-1px);
        }

        .bar {
            background: rgba(167, 139, 250, 0.2);
            border-radius: 3px;
            animation: barPulse ease-in-out infinite;
        }

        @keyframes barPulse {

            0%,
            100% {
                opacity: 0.4;
                transform: scaleY(0.6);
            }

            50% {
                opacity: 1;
                transform: scaleY(1);
            }
        }

        .rate-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 3px;
            align-items: end;
            height: 40px;
        }

        .rate-bar-fill {
            background: linear-gradient(to top, rgba(167, 139, 250, 0.8), rgba(167, 139, 250, 0.3));
            border-radius: 2px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-6">

        {{-- Ghost code background --}}
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none select-none">
            <span class="font-mono text-[28vw] font-medium text-white leading-none tracking-tighter animate-float-code" style="opacity:0.04">429</span>
        </div>

        {{-- Purple ambient glow --}}
        <div class="fixed pointer-events-none" style="top:30%;left:50%;transform:translate(-50%,-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(167,139,250,0.07) 0%,transparent 70%)"></div>

        {{-- Main content --}}
        <div class="relative z-10 text-center max-w-lg">

            {{-- Rate meter visual --}}
            <div class="opacity-0-init animate-fade-up mb-8 flex justify-center">
                <div class="px-5 py-3 rounded-xl" style="background:rgba(167,139,250,0.07);border:1px solid rgba(167,139,250,0.2)">
                    <div class="rate-grid" id="rateGrid">
                    </div>
                    <div class="mt-2 flex items-center justify-between font-mono text-xs" style="color:#a78bfa">
                        <span>rate</span>
                        <span class="text-red-400">limit exceeded</span>
                    </div>
                </div>
            </div>

            {{-- Badge --}}
            <div class="opacity-0-init animate-fade-up mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-mono" style="color:#a78bfa;background:rgba(167,139,250,0.08);border:1px solid rgba(167,139,250,0.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 inline-block animate-pulse"></span>
                TROP DE REQUÊTES — 429
            </div>

            {{-- Title --}}
            <h1 class="opacity-0-init animate-fade-up-delay font-serif text-5xl text-white mb-4 glow leading-tight">
                Ralentissez un peu
            </h1>

            {{-- Message --}}
            <p class="opacity-0-init animate-fade-up-delay2 text-slate-400 text-base leading-relaxed mb-6 font-light">
                Vous avez effectué trop de requêtes en peu de temps.<br>
                Attendez un moment avant de réessayer.
            </p>

            {{-- Retry info --}}
            @if (isset($exception) && method_exists($exception, 'getHeaders') && isset($exception->getHeaders()['Retry-After']))
                <div class="opacity-0-init animate-fade-up-delay2 font-mono text-sm px-4 py-3 rounded-lg mb-8 mx-auto max-w-sm" style="background:rgba(167,139,250,0.06);border:1px solid rgba(167,139,250,0.15);color:#94a3b8">
                    <span style="color:#a78bfa">Retry-After:</span> {{ $exception->getHeaders()['Retry-After'] }}s
                </div>
            @else
                <div class="mb-8"></div>
            @endif

            {{-- Action --}}
            <div class="opacity-0-init animate-fade-up-delay3 flex justify-center">
                <button onclick="window.location.reload()" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Réessayer
                </button>
            </div>

            <p class="opacity-0-init animate-fade-up-delay3 mt-12 font-mono text-xs text-slate-700">
                EducConnect — {{ date('Y') }}
            </p>
        </div>
    </div>

@section('scripts')
    <script>
        // Build the rate bar visualization
        const grid = document.getElementById('rateGrid');
        const heights = [15, 20, 25, 18, 30, 35, 28, 38, 36, 40, 40, 40];
        const colors = [0.3, 0.35, 0.4, 0.35, 0.5, 0.6, 0.5, 0.7, 0.65, 1, 1, 1];
        heights.forEach((h, i) => {
            const bar = document.createElement('div');
            bar.className = 'rate-bar-fill';
            bar.style.height = h + 'px';
            bar.style.opacity = colors[i];
            if (i >= 9) {
                bar.style.background = 'linear-gradient(to top, rgba(248,113,113,0.9), rgba(248,113,113,0.4))';
            }
            grid.appendChild(bar);
        });
    </script>
@endsection
@endsection

