<div class="w-full overflow-x-hidden bg-[#05080f] text-slate-100 antialiased" x-data="{ pageLoaded: false }"
    x-init="const hideLoader = () => {
        pageLoaded = true;
        document.body.classList.remove('overflow-hidden');
    };
    if (document.readyState === 'complete') {
        setTimeout(hideLoader, 300);
    } else {
        window.addEventListener('load', () => setTimeout(hideLoader, 400));
    }
    // Sécurité : cache le loader au bout de 8s max
    setTimeout(hideLoader, 8000);">

    {{-- ===================== PAGE LOADER ===================== --}}
    <div x-show="!pageLoaded" x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#05080f]" style="display: flex;"
        x-cloak>

        {{-- Logo / icône --}}
        <div class="relative mb-8">
            <div
                class="h-16 w-16 sm:h-20 sm:w-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-3xl sm:text-4xl shadow-2xl shadow-indigo-900/50">
                🎓
            </div>
            {{-- Anneau animé --}}
            <div
                class="absolute -inset-2 rounded-2xl border-2 border-transparent border-t-indigo-400 border-r-violet-400 animate-spin">
            </div>
        </div>

        {{-- Nom de l'école --}}
        <p class="text-sm sm:text-base font-semibold text-white tracking-tight mb-1">
            {{ tenant()?->school_name ?? 'Chargement...' }}
        </p>
        <p class="text-xs text-slate-500 mb-6">{{ tenant()?->school_devise }}</p>

        {{-- Barre de progression indéterminée --}}
        <div class="w-40 sm:w-48 h-1 rounded-full bg-white/10 overflow-hidden">
            <div
                class="h-full w-1/2 rounded-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-400 animate-[loader-slide_1.4s_ease-in-out_infinite]">
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes loader-slide {
            0% {
                transform: translateX(-100%);
            }

            50% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(200%);
            }
        }
    </style>

    {{-- ===================== HEADER ===================== --}}
    <header x-data="{
        mobileMenu: false,
        visible: true,
        lastScroll: 0,
        init() {
            window.addEventListener('scroll', () => {
                const current = window.pageYOffset;
                this.visible = current <= 40 ? true : current < this.lastScroll;
                this.lastScroll = current;
            });
        }
    }" :class="visible ? 'translate-y-0' : '-translate-y-full'"
        class="fixed top-0 inset-x-0 z-50 transition-transform duration-300">
        <div class="bg-[#05080f]/80 backdrop-blur-2xl border-b border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex h-16 sm:h-18 items-center justify-between">

                    {{-- Logo --}}
                    <a href="/" class="flex items-center gap-3 group">
                        <div
                            class="h-10 w-10 sm:h-11 sm:w-11 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-xl shadow-lg shadow-indigo-900/40 group-hover:scale-105 transition-transform">
                            🎓
                        </div>
                        <div class="hidden sm:block">
                            <p class="font-bold text-white text-sm tracking-tight leading-tight">
                                {{ tenant()?->school_name }}
                            </p>
                            <p class="text-[10px] text-slate-500 leading-tight">{{ tenant()?->school_devise }}</p>
                        </div>
                    </a>

                    {{-- Nav Desktop --}}
                    <nav class="hidden lg:flex items-center gap-1">
                        <a href="/"
                            class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Accueil</a>
                        <a href="#filieres"
                            class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Filières</a>
                        <a href="#galerie"
                            class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Galerie</a>
                        <a href="#temoignages"
                            class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Témoignages</a>
                        <a href="#contact"
                            class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Contact</a>

                        @auth('tenant')
                            <a href="{{ auth('tenant')->user()->to_profil_route() }}"
                                class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Mon
                                profil</a>
                            @if (!auth('tenant')->user()->hasRole('directeur'))
                                <a href="{{ auth('tenant')->user()->to_space_route() }}"
                                    class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Mon
                                    espace</a>
                            @endif
                            @if (auth('tenant')->user()?->hasRole('directeur'))
                                <a href="{{ route('tenant.dashboard') }}"
                                    class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">Administration</a>
                            @endif
                        @endauth
                    </nav>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3">
                        @guest('tenant')
                            <a href="{{ route('login') }}"
                                class="hidden sm:inline-flex items-center h-10 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white transition shadow-lg shadow-indigo-900/30">
                                Se connecter
                            </a>
                        @endguest

                        @auth('tenant')
                            <div x-data="{ open: false }" class="relative hidden lg:block">
                                <button @click="open = !open"
                                    class="flex items-center gap-2.5 h-10 pl-1.5 pr-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                                    <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                        class="h-7 w-7 rounded-lg object-cover">
                                    <span
                                        class="text-sm font-medium text-slate-200">{{ Auth::guard('tenant')->user()->name }}</span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute right-0 mt-2 w-56 rounded-xl bg-[#0f1523] border border-white/10 shadow-2xl py-1.5 z-50 overflow-hidden">
                                    <a href="{{ auth('tenant')->user()->to_profil_route() }}"
                                        class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5">Mon profil</a>
                                    @if (!auth('tenant')->user()->hasRole('directeur'))
                                        <a href="{{ auth('tenant')->user()->to_space_route() }}"
                                            class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5">Mon espace</a>
                                    @endif
                                    <a href="{{ route('tenant.notifications.center') }}"
                                        class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5">Notifications</a>
                                    <div class="border-t border-white/5 my-1"></div>
                                    <button wire:click='logout'
                                        class="block w-full px-4 py-2.5 text-left text-sm text-rose-400 hover:bg-rose-500/10">Se
                                        déconnecter</button>
                                </div>
                            </div>
                        @endauth

                        <button @click="mobileMenu = !mobileMenu"
                            class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white/5 text-white">
                            <div class="space-y-1.5">
                                <span class="block h-0.5 w-5 bg-white transition-all duration-300"
                                    :class="{ 'rotate-45 translate-y-[7px]': mobileMenu }"></span>
                                <span class="block h-0.5 w-5 bg-white transition-all duration-300"
                                    :class="{ 'opacity-0': mobileMenu }"></span>
                                <span class="block h-0.5 w-5 bg-white transition-all duration-300"
                                    :class="{ '-rotate-45 -translate-y-[7px]': mobileMenu }"></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-transition
            class="lg:hidden bg-[#0a0e17] border-b border-white/5 max-h-[calc(100vh-4rem)] overflow-y-auto">
            <nav class="p-4 space-y-1">
                <a href="/" @click="mobileMenu = false"
                    class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Accueil</a>
                <a href="#filieres" @click="mobileMenu = false"
                    class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Filières</a>
                <a href="#galerie" @click="mobileMenu = false"
                    class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Galerie</a>
                <a href="#temoignages" @click="mobileMenu = false"
                    class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Témoignages</a>
                <a href="#contact" @click="mobileMenu = false"
                    class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Contact</a>

                @guest('tenant')
                    <a href="{{ route('login') }}" @click="mobileMenu = false"
                        class="mt-3 block text-center bg-indigo-600 hover:bg-indigo-500 py-3.5 rounded-xl font-semibold text-sm text-white">
                        Se connecter
                    </a>
                @endguest

                @auth('tenant')
                    <div class="pt-4 mt-3 border-t border-white/5">
                        <div class="flex gap-3 px-4 mb-3">
                            <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                class="h-11 w-11 rounded-xl object-cover">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ Auth::guard('tenant')->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ Auth::guard('tenant')->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ auth('tenant')->user()->to_profil_route() }}"
                            class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Mon profil</a>
                        @if (!auth('tenant')->user()->hasRole('directeur'))
                            <a href="{{ auth('tenant')->user()->to_space_route() }}"
                                class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Mon espace</a>
                        @else
                            <a href="{{ route('tenant.dashboard') }}"
                                class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Administration</a>
                        @endif
                        <a href="{{ route('tenant.notifications.center') }}"
                            class="block px-4 py-3 rounded-xl text-sm text-slate-300 hover:bg-white/5">Notifications</a>
                        <button
                            class="mt-2 w-full py-3 text-sm text-rose-400 hover:bg-rose-500/10 rounded-xl transition">Déconnexion</button>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ===================== HERO ===================== --}}
    <section class="relative min-h-[100svh] flex items-center overflow-hidden">
        {{-- BG --}}
        <div class="absolute inset-0">
            <img src="{{ $this->background_image }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#05080f]/85"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#05080f] via-transparent to-[#05080f]/60"></div>
            {{-- Decorative orbs --}}
            <div class="absolute top-1/4 -left-32 w-96 h-96 rounded-full bg-indigo-600/20 blur-[120px]"></div>
            <div class="absolute bottom-1/4 -right-32 w-80 h-80 rounded-full bg-violet-600/15 blur-[100px]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-28 pb-20 w-full">
            <div class="max-w-3xl">
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-4 py-1.5 text-xs text-slate-300 backdrop-blur-md mb-6">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    Bienvenue à {{ tenant()?->school_name }}
                </div>

                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Former les
                    <span
                        class="bg-gradient-to-r from-indigo-400 via-violet-400 to-cyan-400 bg-clip-text text-transparent">leaders</span>
                    de demain
                </h1>

                @if (tenant('school_vision'))
                    <p class="mt-6 text-base sm:text-lg text-slate-500 max-w-xl leading-relaxed font-mono">
                        {{ tenant('school_vision') }}
                    </p>
                @else
                    <p class="mt-6 text-base sm:text-lg text-slate-500 max-w-xl leading-relaxed">
                        Une éducation d’excellence qui allie discipline, innovation et valeurs humaines pour bâtir
                        l’avenir.
                    </p>
                @endif

                <div class="mt-10 flex flex-wrap gap-3">
                    @guest('tenant')
                        <a href="{{ route('login') }}"
                            class="h-12 px-7 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-white text-sm shadow-xl shadow-indigo-900/40 transition inline-flex items-center gap-2">
                            Me connecter
                            <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    @else
                        @if (auth('tenant')->user()->hasRole('directeur'))
                            <a href="{{ route('tenant.dashboard') }}"
                                class="h-12 px-7 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-white text-sm shadow-xl shadow-indigo-900/40 transition inline-flex items-center gap-2">
                                Espace administrateur
                                <x-lucide-arrow-right class="w-4 h-4" />
                            </a>
                        @else
                            <a href="{{ auth('tenant')->user()->to_space_route() }}"
                                class="h-12 px-7 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-white text-sm shadow-xl shadow-indigo-900/40 transition inline-flex items-center gap-2">
                                Accéder à mon espace
                                <x-lucide-arrow-right class="w-4 h-4" />
                            </a>
                        @endif
                    @endguest
                    <a href="#contact"
                        class="h-12 px-7 rounded-xl border border-white/15 hover:bg-white/5 font-semibold text-white text-sm transition inline-flex items-center">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STATS ===================== --}}
    <section class="relative z-20 -mt-10 sm:-mt-14 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach ([['icon' => '👨‍🎓', 'value' => '1200+', 'label' => 'Élèves'], ['icon' => '🏆', 'value' => '98%', 'label' => 'Taux de réussite'], ['icon' => '👨‍🏫', 'value' => '80+', 'label' => 'Enseignants'], ['icon' => '⭐', 'value' => '25', 'label' => "Années d'excellence"]] as $stat)
                    <div
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-5 sm:p-7 text-center hover:-translate-y-1 hover:border-indigo-500/30 transition-all duration-300 shadow-xl shadow-black/20">
                        <div class="text-3xl sm:text-4xl mb-2">{{ $stat['icon'] }}</div>
                        <p class="text-2xl sm:text-4xl font-black text-white tracking-tight">{{ $stat['value'] }}</p>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== FILIÈRES ===================== --}}
    @if (
        (!tenancy()->tenant->hide_filiars_on_home_page || !tenancy()->tenant->hide_serials_on_home_page) &&
            ($this->filiars->isNotEmpty() || $this->serials->isNotEmpty()))
        <section id="filieres" class="py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-10 sm:mb-14">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Formations</p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                        Nos filières & séries
                    </h2>
                    <p class="mt-3 text-slate-500 max-w-md mx-auto text-sm sm:text-base">
                        Des formations modernes adaptées aux défis du monde professionnel
                    </p>
                </div>

                {{-- Filières --}}
                @php
                    $colors = ['indigo', 'cyan', 'violet', 'emerald', 'amber', 'rose', 'sky', 'fuchsia'];
                @endphp

                @if (!tenancy()->tenant->hide_filiars_on_home_page && $this->filiars->isNotEmpty())
                    <div class="mb-10">
                        <h3
                            class="text-sm font-semibold uppercase tracking-widest text-slate-400 mb-5 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Filières
                        </h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                            @foreach ($this->filiars as $index => $filiere)
                                @php $color = $colors[$index % count($colors)]; @endphp
                                <div
                                    class="group rounded-xl bg-[#0f1523] border border-white/[0.06] p-4 sm:p-5 hover:border-{{ $color }}-500/30 hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/10">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-{{ $color }}-500/10 border border-{{ $color }}-500/20 flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                                            🎓
                                        </div>
                                        @if ($filiere->code)
                                            <span
                                                class="text-[10px] font-bold tracking-wider uppercase text-{{ $color }}-400/80 bg-{{ $color }}-500/10 px-2 py-0.5 rounded-md">
                                                {{ $filiere->code }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm sm:text-base font-bold text-white mb-1.5 leading-snug">
                                        {{ $filiere->name }}
                                    </h3>
                                    @if ($filiere->description)
                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                            {{ $filiere->description }}
                                        </p>
                                    @endif
                                    <div
                                        class="mt-4 text-xs font-medium text-{{ $color }}-400 group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1">
                                        En savoir plus <span>→</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Séries --}}
                @if (!tenancy()->tenant->hide_serials_on_home_page && $this->serials->isNotEmpty())
                    <div>
                        <h3
                            class="text-sm font-semibold uppercase tracking-widest text-slate-400 mb-5 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                            Séries
                        </h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                            @foreach ($this->serials as $index => $serie)
                                @php $color = $colors[($index + 3) % count($colors)]; @endphp
                                <div
                                    class="group rounded-xl bg-[#0f1523] border border-white/[0.06] p-4 sm:p-5 hover:border-{{ $color }}-500/30 hover:-translate-y-0.5 transition-all duration-300 shadow-lg shadow-black/10">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-{{ $color }}-500/10 border border-{{ $color }}-500/20 flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                                            📚
                                        </div>
                                        @if ($serie->code)
                                            <span
                                                class="text-[10px] font-bold tracking-wider uppercase text-{{ $color }}-400/80 bg-{{ $color }}-500/10 px-2 py-0.5 rounded-md">
                                                {{ $serie->code }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm sm:text-base font-bold text-white mb-1.5 leading-snug">
                                        {{ $serie->name }}
                                    </h3>
                                    @if ($serie->description)
                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                            {{ $serie->description }}
                                        </p>
                                    @endif
                                    <div
                                        class="mt-4 text-xs font-medium text-{{ $color }}-400 group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1">
                                        En savoir plus <span>→</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($this->filiars->isEmpty() && $this->serials->isEmpty())
                    <div class="text-center py-12 text-slate-500 text-sm">
                        Aucune filière ou série disponible pour le moment.
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ===================== GALERIE ===================== --}}
    @if (!tenancy()->tenant->hide_galleries_on_home_page && count($this->galleries))
        <section id="galerie" class="relative py-20 sm:py-28 overflow-hidden">
            {{-- Fond --}}
            <div class="absolute inset-0 bg-[#0a0e17]"></div>
            <div
                class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent">
            </div>
            <div
                class="absolute -top-32 left-1/4 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-32 right-1/4 w-80 h-80 rounded-full bg-violet-600/10 blur-3xl pointer-events-none">
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6">

                {{-- En-tête --}}
                <div class="text-center mb-12 sm:mb-16">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">
                        Nostalgie
                    </p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                        La vie à
                        <span
                            class="bg-gradient-to-r from-indigo-400 via-violet-400 to-sky-400 bg-clip-text text-transparent">
                            {{ tenant('school_name') }}
                        </span>
                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-500 max-w-xl mx-auto">
                        Moments, souvenirs et scènes du quotidien de notre établissement
                    </p>
                </div>

                {{-- Grille --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach ($this->galleries as $gallery)
                        <article
                            class="group relative aspect-square overflow-hidden rounded-2xl bg-[#0f1523] border border-white/[0.04] shadow-lg shadow-black/20">

                            <img src="{{ $gallery->path_url }}" alt="{{ $gallery->title ?? 'Image galerie' }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                                loading="lazy">

                            {{-- Overlay dégradé permanent (bas) --}}
                            <div
                                class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/80 via-black/30 to-transparent pointer-events-none">
                            </div>

                            {{-- Overlay hover --}}
                            <div
                                class="absolute inset-0 bg-indigo-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>

                            {{-- Détails --}}
                            <div
                                class="absolute inset-x-0 bottom-0 p-3 sm:p-4 translate-y-1 group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="text-xs sm:text-sm font-semibold text-white truncate drop-shadow-sm">
                                    {{ $gallery->title ?: 'Sans titre' }}
                                </h3>

                                @if ($gallery->description)
                                    <p
                                        class="mt-1 text-[10px] sm:text-xs text-slate-300/90 line-clamp-2 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-75">
                                        {{ $gallery->description }}
                                    </p>
                                @endif

                                @if ($gallery->created_at)
                                    <p
                                        class="mt-1.5 text-[9px] sm:text-[10px] font-mono text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                                        {{ $gallery->created_at->format('d M Y') }}
                                    </p>
                                @endif
                            </div>

                            {{-- Liseré hover --}}
                            <div
                                class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-white/0 group-hover:ring-indigo-400/30 transition-all duration-300 pointer-events-none">
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== TÉMOIGNAGES ===================== --}}

    @if (!tenancy()->tenant->hide_testimonials_on_home_page && count($this->testimonials))
        <section id="temoignages" class="py-20 sm:py-28 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 sm:mb-16">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Communauté</p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                        Ce qu’ils disent de nous
                    </h2>
                    @auth('tenant')
                        <button class="py-4 px-12 my-5 rounded-2xl bg-lime-600 hover:bg-lime-800 text-lime-200"
                            onclick="Livewire.dispatch('open-testimonial-modal')">
                            Laisser un témoignage | Commentaire
                        </button>
                    @endauth
                </div>
            </div>

            {{-- Carrousel auto + infini + manuel --}}
            <div class="relative" x-data="{
                speed: 0.5,
                isPaused: false,
                isDragging: false,
                startX: 0,
                scrollStart: 0,
            
                init() {
                    const track = this.$refs.track;
                    const items = Array.from(track.children);
            
                    // Clone intelligent si peu de témoignages
                    const minCards = 8;
                    let clonesNeeded = 1;
            
                    if (items.length > 0 && items.length < minCards) {
                        clonesNeeded = Math.ceil(minCards / items.length);
                    }
            
                    const originalHTML = track.innerHTML;
                    for (let i = 0; i < clonesNeeded; i++) {
                        track.innerHTML += originalHTML;
                    }
            
                    this.originalWidth = track.scrollWidth / (clonesNeeded + 1);
            
                    const animate = () => {
                        if (!this.isPaused && !this.isDragging) {
                            track.scrollLeft += this.speed;
            
                            if (track.scrollLeft >= this.originalWidth) {
                                track.scrollLeft -= this.originalWidth;
                            }
                        }
                        requestAnimationFrame(animate);
                    };
            
                    requestAnimationFrame(animate);
            
                    // Pause au survol
                    track.addEventListener('mouseenter', () => this.isPaused = true);
                    track.addEventListener('mouseleave', () => {
                        if (!this.isDragging) this.isPaused = false;
                    });
            
                    // Drag souris
                    track.addEventListener('mousedown', (e) => {
                        this.isDragging = true;
                        this.isPaused = true;
                        this.startX = e.pageX - track.offsetLeft;
                        this.scrollStart = track.scrollLeft;
                        track.style.cursor = 'grabbing';
                        track.style.userSelect = 'none';
                    });
            
                    window.addEventListener('mouseup', () => {
                        if (this.isDragging) {
                            this.isDragging = false;
                            track.style.cursor = 'grab';
                            track.style.userSelect = '';
                            setTimeout(() => this.isPaused = false, 800);
                        }
                    });
            
                    window.addEventListener('mousemove', (e) => {
                        if (!this.isDragging) return;
                        e.preventDefault();
                        const x = e.pageX - track.offsetLeft;
                        const walk = (x - this.startX) * 1.4;
                        track.scrollLeft = this.scrollStart - walk;
                    });
            
                    // Touch mobile
                    track.addEventListener('touchstart', () => this.isPaused = true, { passive: true });
                    track.addEventListener('touchend', () => {
                        setTimeout(() => this.isPaused = false, 1000);
                    }, { passive: true });
                },
            
                scrollBy(amount) {
                    this.$refs.track.scrollBy({ left: amount, behavior: 'smooth' });
                    this.isPaused = true;
                    setTimeout(() => this.isPaused = false, 1200);
                }
            }">

                {{-- Boutons navigation --}}
                <div
                    class="hidden sm:flex absolute top-1/2 -translate-y-1/2 left-3 right-3 z-20 justify-between pointer-events-none">
                    <button @click="scrollBy(-360)"
                        class="pointer-events-auto w-11 h-11 rounded-full bg-[#0f1523]/90 border border-white/10 backdrop-blur-md
                       flex items-center justify-center text-white hover:bg-indigo-600 hover:border-indigo-500
                       transition shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="scrollBy(360)"
                        class="pointer-events-auto w-11 h-11 rounded-full bg-[#0f1523]/90 border border-white/10 backdrop-blur-md
                       flex items-center justify-center text-white hover:bg-indigo-600 hover:border-indigo-500
                       transition shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {{-- Track --}}
                <div x-ref="track"
                    class="flex gap-5 sm:gap-6 overflow-x-auto pb-6 px-4 sm:px-6 select-none cursor-grab
                    [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

                    @foreach ($this->testimonials as $testimonial)
                        @php
                            $author = $testimonial->user;
                            $authorName = $author?->getFullName() ?? 'Anonyme';
                            $authorPhoto = $author?->profil_photo_url ?? ($author?->profile_photo_url ?? null);
                            $initial = strtoupper(mb_substr($authorName, 0, 1));
                        @endphp

                        <div class="flex-none w-[300px] sm:w-[340px]">
                            <div
                                class="h-full rounded-2xl bg-[#0f1523] border border-white/[0.06] p-6 sm:p-7 flex flex-col
                                hover:border-indigo-500/30 transition-all duration-300 shadow-xl shadow-black/20">

                                <div class="text-3xl text-indigo-500/40 mb-2 leading-none">"</div>

                                <p class="text-sm sm:text-base text-slate-300 leading-relaxed flex-1 italic">
                                    {{ $testimonial->content }}
                                </p>

                                <div class="mt-5 pt-4 border-t border-white/5 flex items-center gap-3">
                                    @if ($authorPhoto)
                                        <img src="{{ $authorPhoto }}" alt="{{ $authorName }}"
                                            class="h-10 w-10 rounded-xl object-cover ring-2 ring-white/10">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-xl bg-indigo-500/20 border border-indigo-500/20
                                            flex items-center justify-center text-sm font-bold text-indigo-300">
                                            {{ $initial }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $authorName }}</p>
                                        <p class="text-[11px] text-slate-500">
                                            {{ implode(' - ', $testimonial->user->roles->pluck('name')->toArray()) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (!tenancy()->tenant->hide_personnels_on_home_page && count($this->personnels))
        {{-- ===================== ÉQUIPE / PERSONNEL ===================== --}}
        <section id="equipe" class="py-20 sm:py-28 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 sm:mb-16">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Équipe</p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                        Le personnel administratif
                    </h2>
                    <p class="mt-4 text-slate-500 max-w-md mx-auto text-sm sm:text-base">
                        Des professionnels passionnés au service de la réussite de chaque élève
                    </p>
                </div>
            </div>

            {{-- Carrousel auto + infini + manuel --}}
            <div class="relative" x-data="{
                speed: 0.55,
                isPaused: false,
                isDragging: false,
                startX: 0,
                scrollStart: 0,
            
                init() {
                    const track = this.$refs.track;
                    const items = Array.from(track.children);
            
                    // Si trop peu d'éléments, on clone plusieurs fois pour avoir un vrai loop
                    // (minimum ~8-10 cartes pour que le défilement soit fluide)
                    const minCards = 8;
                    let clonesNeeded = 1;
            
                    if (items.length > 0 && items.length < minCards) {
                        clonesNeeded = Math.ceil(minCards / items.length);
                    }
            
                    // On clone le contenu original (clonesNeeded fois)
                    const originalHTML = track.innerHTML;
                    for (let i = 0; i < clonesNeeded; i++) {
                        track.innerHTML += originalHTML;
                    }
            
                    // Largeur d'un set original (pour le reset seamless)
                    this.originalWidth = track.scrollWidth / (clonesNeeded + 1);
            
                    let animationId;
            
                    const animate = () => {
                        if (!this.isPaused && !this.isDragging) {
                            track.scrollLeft += this.speed;
            
                            // Reset seamless
                            if (track.scrollLeft >= this.originalWidth) {
                                track.scrollLeft -= this.originalWidth;
                            }
                        }
                        animationId = requestAnimationFrame(animate);
                    };
            
                    animationId = requestAnimationFrame(animate);
            
                    // Pause au survol
                    track.addEventListener('mouseenter', () => this.isPaused = true);
                    track.addEventListener('mouseleave', () => {
                        if (!this.isDragging) this.isPaused = false;
                    });
            
                    // Support drag (souris)
                    track.addEventListener('mousedown', (e) => {
                        this.isDragging = true;
                        this.isPaused = true;
                        this.startX = e.pageX - track.offsetLeft;
                        this.scrollStart = track.scrollLeft;
                        track.style.cursor = 'grabbing';
                        track.style.userSelect = 'none';
                    });
            
                    window.addEventListener('mouseup', () => {
                        if (this.isDragging) {
                            this.isDragging = false;
                            track.style.cursor = 'grab';
                            track.style.userSelect = '';
                            // On reprend l'auto-scroll après un petit délai
                            setTimeout(() => this.isPaused = false, 800);
                        }
                    });
            
                    window.addEventListener('mousemove', (e) => {
                        if (!this.isDragging) return;
                        e.preventDefault();
                        const x = e.pageX - track.offsetLeft;
                        const walk = (x - this.startX) * 1.4;
                        track.scrollLeft = this.scrollStart - walk;
                    });
            
                    // Touch (mobile)
                    track.addEventListener('touchstart', () => {
                        this.isPaused = true;
                    }, { passive: true });
            
                    track.addEventListener('touchend', () => {
                        setTimeout(() => this.isPaused = false, 1000);
                    }, { passive: true });
                },
            
                scrollBy(amount) {
                    this.$refs.track.scrollBy({ left: amount, behavior: 'smooth' });
                    this.isPaused = true;
                    setTimeout(() => this.isPaused = false, 1200);
                }
            }">

                {{-- Boutons de navigation --}}
                <div
                    class="hidden sm:flex absolute top-1/2 -translate-y-1/2 left-3 right-3 z-20 justify-between pointer-events-none">
                    <button @click="scrollBy(-340)"
                        class="pointer-events-auto w-11 h-11 rounded-full bg-[#0f1523]/90 border border-white/10 backdrop-blur-md
                       flex items-center justify-center text-white hover:bg-indigo-600 hover:border-indigo-500
                       transition shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="scrollBy(340)"
                        class="pointer-events-auto w-11 h-11 rounded-full bg-[#0f1523]/90 border border-white/10 backdrop-blur-md
                       flex items-center justify-center text-white hover:bg-indigo-600 hover:border-indigo-500
                       transition shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {{-- Track --}}
                <div x-ref="track"
                    class="flex gap-5 sm:gap-6 overflow-x-auto pb-6 px-4 sm:px-6 select-none cursor-grab
                    [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

                    @foreach ($this->personnels as $member)
                        <div class="flex-none w-[300px] sm:w-[320px]">
                            <div
                                class="h-full rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden
                                hover:border-indigo-500/30 transition-all duration-300 shadow-xl shadow-black/20">

                                {{-- Photo --}}
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ $member->profil_photo_url }}" alt="{{ $member->full_name }}"
                                        class="w-full h-full object-cover" loading="lazy">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-[#0f1523] via-transparent to-transparent">
                                    </div>
                                </div>

                                {{-- Contenu --}}
                                <div class="p-5 sm:p-6 -mt-8 relative">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-white leading-tight">
                                            {{ $member->full_name }}
                                        </h3>
                                        <p class="text-sm text-indigo-400 font-medium mt-0.5">{{ $member->title }}</p>
                                    </div>

                                    <p class="text-sm text-slate-400 leading-relaxed italic">
                                        « {{ $member->description ?? __getCitation() }} »
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== CTA ===================== --}}
    <section class="py-16 sm:py-20 px-4 sm:px-6">
        <div
            class="max-w-4xl mx-auto rounded-3xl bg-gradient-to-br from-indigo-600/20 via-violet-600/10 to-transparent border border-indigo-500/20 p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
            <h2 class="relative text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight">
                Prêt à rejoindre l’excellence ?
            </h2>
            <p class="relative mt-3 text-slate-400 text-sm sm:text-base max-w-md mx-auto">
                Connectez-vous à votre espace ou contactez-nous pour en savoir plus sur nos formations.
            </p>
            <div class="relative mt-8 flex flex-wrap justify-center gap-3">
                @guest('tenant')
                    <a href="{{ route('login') }}"
                        class="py-3 inline-flex items-center px-7 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-white text-sm shadow-xl shadow-indigo-900/40 transition">
                        <span>Se connecter</span>
                    </a>
                @endguest
                <a href="#contact"
                    class="px-7 py-4 rounded-xl border border-white/15 hover:bg-white/5 font-semibold text-white text-sm transition inline-flex items-center">
                    <span>Nous contacter</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer id="contact" class="border-t border-white/[0.04] bg-[#05080f] pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-lg">
                            🎓</div>
                        <h3 class="font-bold text-white">{{ tenant('school_name') }}</h3>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ tenant('school_devise') }}</p>
                </div>

                @auth('tenant')
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">Navigation</h4>
                        <ul class="space-y-2.5 text-sm text-slate-500">
                            <li><a href="/" class="hover:text-white transition">Accueil</a></li>
                            @if (!auth('tenant')->user()->hasRole('directeur'))
                                <li><a href="{{ auth('tenant')->user()->to_space_route() }}"
                                        class="hover:text-white transition">Mon espace</a></li>
                            @else
                                <li><a href="{{ route('tenant.dashboard') }}"
                                        class="hover:text-white transition">Administration</a></li>
                            @endif
                            <li><a href="{{ auth('tenant')->user()->to_profil_route() }}"
                                    class="hover:text-white transition">Mon profil</a></li>
                            <li><a href="{{ route('tenant.notifications.center') }}"
                                    class="hover:text-white transition">Notifications</a></li>
                            <li>
                                <button class="text-lime-700 hover:text-lime-400"
                                    onclick="Livewire.dispatch('open-testimonial-modal')">
                                    Laisser un témoignage | Commentaire
                                </button>
                            </li>
                        </ul>
                    </div>
                @endauth

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">Contact</h4>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li>{{ tenant()?->adresse }}</li>
                        <li>+229 {{ tenant()?->contacts }}</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">Notre vision</h4>

                    @if (tenant('school_vision'))
                        <p class="text-sm text-slate-500 leading-relaxed italic">
                            {{ tenant('school_vision') }}
                        </p>
                    @else
                        <p class="text-sm text-slate-500 leading-relaxed italic">
                            Promouvoir l’excellence et contribuer à l’insertion professionnelle de nos apprenants pour
                            le
                            développement national.
                        </p>
                    @endif
                </div>
            </div>

            <div class="border-t border-white/[0.04] mt-12 pt-6 text-center text-xs text-slate-600">
                © {{ date('Y') }} {{ tenant()?->school_name }} — Tous droits réservés
            </div>
        </div>
    </footer>
</div>

