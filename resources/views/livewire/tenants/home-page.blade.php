<div class="w-full overflow-x-hidden bg-[#05080f] text-slate-100 antialiased">

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
                                    <button
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
            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop"
                alt="" class="w-full h-full object-cover">
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

                <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-xl leading-relaxed">
                    Une éducation d’excellence qui allie discipline, innovation et valeurs humaines pour bâtir l’avenir.
                </p>

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
    <section id="filieres" class="py-20 sm:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Formations</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">Nos filières & séries
                </h2>
                <p class="mt-4 text-slate-500 max-w-md mx-auto text-sm sm:text-base">
                    Des formations modernes adaptées aux défis du monde professionnel
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach ([['icon' => '💻', 'title' => 'Informatique & Digital', 'desc' => 'Développement web, intelligence artificielle, cybersécurité et data science.', 'color' => 'indigo'], ['icon' => '⚙️', 'title' => 'Génie Technique', 'desc' => 'Électrotechnique, mécanique, génie civil et maintenance industrielle.', 'color' => 'cyan'], ['icon' => '📊', 'title' => 'Gestion & Management', 'desc' => 'Finance, marketing, ressources humaines et entrepreneuriat.', 'color' => 'violet']] as $filiere)
                    <div
                        class="group rounded-2xl bg-[#0f1523] border border-white/[0.06] p-6 sm:p-8 hover:border-{{ $filiere['color'] }}-500/30 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-black/10">
                        <div
                            class="w-14 h-14 rounded-2xl bg-{{ $filiere['color'] }}-500/10 border border-{{ $filiere['color'] }}-500/20 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform">
                            {{ $filiere['icon'] }}
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-white mb-2">{{ $filiere['title'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $filiere['desc'] }}</p>
                        <div
                            class="mt-6 text-sm font-medium text-{{ $filiere['color'] }}-400 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                            En savoir plus <span>→</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== GALERIE ===================== --}}
    <section id="galerie" class="py-20 sm:py-28 bg-[#0a0e17]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Campus</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                    La vie à {{ tenant('school_name') }}
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @for ($i = 7; $i <= randomNumber(11, 18); $i++)
                    <div class="aspect-square overflow-hidden rounded-2xl group relative">
                        <img src="{{ asset('images/school' . $i . '.jpg') }}" alt=""
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            loading="lazy">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- ===================== TÉMOIGNAGES ===================== --}}
    <section id="temoignages" class="py-20 sm:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400 mb-3">Communauté</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
                    Ce qu’ils disent de nous
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach ([['quote' => "Cette école m'a donné bien plus que des connaissances : elle m'a appris à croire en moi.", 'name' => 'Jean Dupont', 'role' => 'Promotion 2024 · Informatique'], ['quote' => 'Les enseignants sont passionnés et disponibles. Une véritable famille.', 'name' => 'Marie Konan', 'role' => 'Promotion 2023 · Gestion'], ['quote' => "Grâce à cette formation, j'ai pu intégrer une grande entreprise dès ma sortie.", 'name' => 'Alain Traoré', 'role' => 'Promotion 2025 · Génie Technique']] as $t)
                    <div class="rounded-2xl bg-[#0f1523] border border-white/[0.06] p-6 sm:p-8 flex flex-col">
                        <div class="text-3xl text-indigo-500/40 mb-4">"</div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed flex-1 italic">
                            {{ $t['quote'] }}</p>
                        <div class="mt-6 pt-5 border-t border-white/5 flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-xl bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center text-sm font-bold text-indigo-300">
                                {{ str()->substr($t['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $t['name'] }}</p>
                                <p class="text-[11px] text-slate-500">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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
                        class="h-12 px-7 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-white text-sm shadow-xl shadow-indigo-900/40 transition">
                        Se connecter
                    </a>
                @endguest
                <a href="#contact"
                    class="h-12 px-7 rounded-xl border border-white/15 hover:bg-white/5 font-semibold text-white text-sm transition">
                    Nous contacter
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
                    <p class="text-sm text-slate-500 leading-relaxed italic">
                        Promouvoir l’excellence et contribuer à l’insertion professionnelle de nos apprenants pour le
                        développement national.
                    </p>
                </div>
            </div>

            <div class="border-t border-white/[0.04] mt-12 pt-6 text-center text-xs text-slate-600">
                © {{ date('Y') }} {{ tenant()?->school_name }} — Tous droits réservés
            </div>
        </div>
    </footer>
</div>
