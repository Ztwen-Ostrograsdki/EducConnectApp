<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
    class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-6 py-16">
    {{-- Fond décoratif : halos dégradés animés --}}
    <div
        class="pointer-events-none absolute -top-40 -left-40 h-96 w-96 rounded-full bg-indigo-600/20 blur-3xl animate-pulse">
    </div>
    <div class="pointer-events-none absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl animate-pulse"
        style="animation-delay: 1.5s;"></div>
    <div class="pointer-events-none absolute top-1/4 right-1/4 h-72 w-72 rounded-full bg-purple-600/10 blur-3xl animate-pulse"
        style="animation-delay: 0.7s;"></div>

    {{-- Grille subtile en overlay --}}
    <div
        class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:48px_48px]">
    </div>

    {{-- Icônes flottantes thème école --}}
    <x-lucide-graduation-cap
        class="pointer-events-none absolute top-[12%] left-[10%] h-10 w-10 text-indigo-400/30 animate-float-slow" />
    <x-lucide-backpack
        class="pointer-events-none absolute bottom-[24%] right-[12%] h-8 w-8 text-purple-300/20 animate-float-slow" />

    {{-- Icônes en mouvement multi-directionnel dans toute la page --}}
    <x-lucide-book-open
        class="pointer-events-none absolute top-[20%] right-[14%] h-8 w-8 text-purple-400/25 animate-drift-book" />
    <x-lucide-pencil-ruler
        class="pointer-events-none absolute bottom-[18%] left-[16%] h-9 w-9 text-indigo-300/25 animate-drift-pencil" />
    <x-lucide-calculator
        class="pointer-events-none absolute top-[45%] left-[6%] h-7 w-7 text-indigo-400/20 animate-drift-calculator hidden sm:block" />
    <x-lucide-ruler
        class="pointer-events-none absolute top-[50%] right-[8%] h-7 w-7 text-purple-400/20 animate-drift-ruler hidden sm:block" />

    {{-- Carte principale --}}
    <div x-show="show" x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        class="relative z-10 w-full max-w-xl font-mono">
        <div class="rounded-3xl p-8 text-center sm:p-12">

            {{-- Illustration scolaire (SVG inline, sans fond) --}}
            <a href="{{ url('/') }}" class="relative block mx-auto mb-6 h-40 w-40 sm:h-48 sm:w-48 group">
                <span
                    class="absolute inset-0 rounded-full bg-indigo-500/10 blur-2xl group-hover:bg-orange-500/40"></span>
                <svg viewBox="0 0 200 200" class="relative h-full w-full" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="roofGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#818cf8" />
                            <stop offset="100%" stop-color="#6366f1" />
                        </linearGradient>
                        <linearGradient id="bookGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#a78bfa" />
                            <stop offset="100%" stop-color="#818cf8" />
                        </linearGradient>
                    </defs>

                    {{-- bâtiment école --}}
                    <rect x="55" y="95" width="90" height="60" rx="4" fill="#1e293b" stroke="#334155"
                        stroke-width="2" />
                    <polygon points="50,98 100,60 150,98" fill="url(#roofGrad)" />
                    <rect x="93" y="55" width="14" height="14" fill="url(#roofGrad)" />
                    <rect x="70" y="112" width="16" height="16" rx="2" fill="#334155" stroke="#475569"
                        stroke-width="1.5" />
                    <rect x="114" y="112" width="16" height="16" rx="2" fill="#334155" stroke="#475569"
                        stroke-width="1.5" />
                    <rect x="92" y="130" width="16" height="25" rx="2" fill="#4338ca" />

                    {{-- drapeau --}}
                    <line x1="100" y1="60" x2="100" y2="40" stroke="#94a3b8"
                        stroke-width="2" />
                    <path d="M100 40 L118 45 L100 50 Z" fill="#a78bfa" />

                    {{-- livre ouvert au sol --}}
                    <path d="M35 165 Q55 155 75 165 L75 172 Q55 164 35 172 Z" fill="url(#bookGrad)" opacity="0.9" />
                    <path d="M75 165 Q95 155 115 165 L115 172 Q95 164 75 172 Z" fill="url(#bookGrad)" opacity="0.7" />

                    {{-- étoiles / éclat --}}
                    <circle cx="150" cy="70" r="2.5" fill="#c4b5fd" />
                    <circle cx="160" cy="85" r="1.5" fill="#a5b4fc" />
                    <circle cx="45" cy="80" r="2" fill="#a5b4fc" />
                </svg>
            </a>

            {{-- Badge statut --}}
            <div
                class="mb-5 inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-4 py-1.5 animate-pulse">
                <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-xs font-medium tracking-wide text-amber-300 uppercase">Maintenance en cours</span>
            </div>

            {{-- Titre --}}
            <h1 class="mb-3 text-xl font-bold text-white sm:text-3xl">Votre école
                <span class="text-sky-600">{{ tenant('id') }}</span>
                <br>
                <span class="text-lg">est en maintenance</span>
            </h1>

            {{-- Description --}}
            <p class="mb-6 text-sm leading-relaxed text-slate-400 sm:text-base">
                Nous effectuons actuellement une intervention technique sur la plateforme afin d'améliorer votre
                expérience.
                L'accès sera automatiquement rétabli dans quelques instants. Merci de votre patience.
            </p>

            {{-- Barre de progression indéterminée --}}
            <div class="mx-auto mb-8 h-1.5 w-full max-w-xs overflow-hidden rounded-full bg-white/10">
                <div
                    class="h-full w-1/3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 animate-progress-indeterminate">
                </div>
            </div>

            {{-- Illustration : équipe en pleine rénovation (SVG inline, sans fond) --}}
            <div class="relative mx-auto mb-8 h-36 w-full max-w-sm sm:h-44">
                <svg viewBox="0 0 320 160" class="h-full w-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="wallGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#334155" />
                            <stop offset="100%" stop-color="#1e293b" />
                        </linearGradient>
                    </defs>

                    {{-- mur du bâtiment en rénovation --}}
                    <rect x="150" y="20" width="150" height="120" rx="4" fill="url(#wallGrad)"
                        stroke="#475569" stroke-width="2" />
                    <rect x="170" y="45" width="24" height="24" rx="2" fill="#0f172a"
                        stroke="#475569" />
                    <rect x="210" y="45" width="24" height="24" rx="2" fill="#4338ca"
                        opacity="0.6" />
                    <rect x="170" y="90" width="24" height="24" rx="2" fill="#0f172a"
                        stroke="#475569" />

                    {{-- échafaudage --}}
                    <g stroke="#94a3b8" stroke-width="2">
                        <line x1="140" y1="150" x2="140" y2="20" />
                        <line x1="160" y1="150" x2="160" y2="20" />
                        <line x1="140" y1="50" x2="160" y2="50" />
                        <line x1="140" y1="90" x2="160" y2="90" />
                        <line x1="140" y1="130" x2="160" y2="130" />
                    </g>

                    {{-- échelle --}}
                    <g stroke="#a5b4fc" stroke-width="2.5">
                        <line x1="105" y1="150" x2="120" y2="55" />
                        <line x1="120" y1="150" x2="135" y2="55" />
                        <line x1="107" y1="130" x2="132" y2="130" />
                        <line x1="110" y1="105" x2="134" y2="105" />
                        <line x1="113" y1="80" x2="136" y2="80" />
                    </g>

                    {{-- ouvrier n°1 en haut de l'échelle avec rouleau de peinture --}}
                    <g>
                        <circle cx="127" cy="45" r="8" fill="#fcd9b8" />
                        <path d="M119 45 a8 8 0 0 1 16 0" fill="#f59e0b" />
                        <rect x="118" y="52" width="18" height="24" rx="5" fill="#6366f1" />
                        <rect x="121" y="76" width="6" height="16" rx="2" fill="#334155" />
                        <rect x="129" y="76" width="6" height="16" rx="2" fill="#334155" />
                        <line x1="136" y1="58" x2="152" y2="48" stroke="#fcd9b8"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="150" y="40" width="14" height="8" rx="2" fill="#a78bfa"
                            transform="rotate(-25 150 40)" />
                    </g>

                    {{-- ouvrier n°2 au sol avec caisse à outils --}}
                    <g>
                        <circle cx="70" cy="110" r="8" fill="#e7b58e" />
                        <path d="M62 110 a8 8 0 0 1 16 0" fill="#334155" />
                        <rect x="61" y="117" width="18" height="24" rx="5" fill="#f59e0b" />
                        <rect x="63" y="141" width="6" height="14" rx="2" fill="#1e293b" />
                        <rect x="71" y="141" width="6" height="14" rx="2" fill="#1e293b" />
                        <line x1="61" y1="124" x2="46" y2="132" stroke="#e7b58e"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="135" width="20" height="14" rx="2" fill="#4338ca" />
                        <rect x="41" y="129" width="8" height="8" fill="#818cf8" />
                    </g>

                    {{-- éclaboussures / poussière de travaux --}}
                    <circle cx="95" cy="40" r="2" fill="#a5b4fc" opacity="0.7" />
                    <circle cx="180" cy="15" r="2" fill="#c4b5fd" opacity="0.7" />
                    <circle cx="30" cy="95" r="2" fill="#a5b4fc" opacity="0.6" />

                    {{-- brouette au sol --}}
                    <g>
                        <circle cx="255" cy="148" r="7" fill="none" stroke="#94a3b8"
                            stroke-width="2" />
                        <path d="M240 130 L270 130 L263 148 L247 148 Z" fill="#4338ca" opacity="0.8" />
                    </g>
                </svg>
            </div>

            <a href="{{ url('/') }}"
                class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-900/40 transition-all hover:scale-[1.02] hover:shadow-indigo-800/50 active:scale-[0.98]">
                <x-lucide-home class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" />
                Retour à l'accueil
            </a>

            <p class="mt-8 text-sm animate-pulse text-orange-600">Votre espace sera de nouveau accessible dans quelques
                instants</p>

            <p class="mt-8 text-xs text-slate-600">EducConnect &middot; Merci de réessayer un peu plus tard</p>
        </div>
    </div>

    @once
        <style>
            @keyframes float-slow {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg);
                }

                50% {
                    transform: translateY(-14px) rotate(4deg);
                }
            }

            @keyframes progress-indeterminate {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(300%);
                }
            }

            /* Trajectoires libres pour les icônes qui se déplacent dans toute la page */
            @keyframes drift-book {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                }

                20% {
                    transform: translate(60px, -50px) rotate(20deg);
                }

                40% {
                    transform: translate(120px, 10px) rotate(-15deg);
                }

                60% {
                    transform: translate(50px, 70px) rotate(25deg);
                }

                80% {
                    transform: translate(-40px, 30px) rotate(-10deg);
                }

                100% {
                    transform: translate(0, 0) rotate(0deg);
                }
            }

            @keyframes drift-pencil {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                }

                25% {
                    transform: translate(-70px, -40px) rotate(-25deg);
                }

                50% {
                    transform: translate(-30px, 40px) rotate(15deg);
                }

                75% {
                    transform: translate(60px, 60px) rotate(-20deg);
                }

                100% {
                    transform: translate(0, 0) rotate(0deg);
                }
            }

            @keyframes drift-calculator {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                }

                30% {
                    transform: translate(80px, 30px) rotate(12deg);
                }

                60% {
                    transform: translate(40px, -60px) rotate(-18deg);
                }

                100% {
                    transform: translate(0, 0) rotate(0deg);
                }
            }

            @keyframes drift-ruler {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                }

                35% {
                    transform: translate(-60px, 50px) rotate(-20deg);
                }

                70% {
                    transform: translate(-100px, -20px) rotate(15deg);
                }

                100% {
                    transform: translate(0, 0) rotate(0deg);
                }
            }

            .animate-float-slow {
                animation: float-slow 6s ease-in-out infinite;
            }

            .animate-progress-indeterminate {
                animation: progress-indeterminate 1.4s ease-in-out infinite;
            }

            .animate-drift-book {
                animation: drift-book 14s ease-in-out infinite;
            }

            .animate-drift-pencil {
                animation: drift-pencil 17s ease-in-out infinite;
            }

            .animate-drift-calculator {
                animation: drift-calculator 12s ease-in-out infinite;
            }

            .animate-drift-ruler {
                animation: drift-ruler 15s ease-in-out infinite;
            }
        </style>
    @endonce
</div>

