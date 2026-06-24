<div class="w-full overflow-x-hidden">

    <header x-data="{
        mobileMenu: false,
        visible: true,
        lastScroll: 0,
    
        init() {
            window.addEventListener('scroll', () => {
                const current = window.pageYOffset;
                if (current <= 20) {
                    this.visible = true;
                } else {
                    this.visible = current < this.lastScroll;
                }
                this.lastScroll = current;
            });
        }
    }" :class="visible ? 'translate-y-0' : '-translate-y-full'"
        class="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-sky-950/80 backdrop-blur-xl border-b border-white/10">

        {{-- Overlay Mobile --}}
        <div x-show="mobileMenu" x-transition.opacity @click="mobileMenu = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm lg:hidden z-40">
        </div>

        <div class="max-w-7xl mx-auto px-5">
            <div class="flex h-20 items-center justify-between">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3">
                    <div
                        class="h-12 w-12 flex items-center justify-center rounded-2xl bg-indigo-600 text-3xl shadow-xl">
                        🎓
                    </div>
                    <div>
                        <h2 class="font-bold text-white text-xl tracking-tight">
                            {{ tenant()?->school_name }}
                        </h2>
                        <p class="text-xs text-sky-200">{{ tenant()?->school_devise }}</p>
                    </div>
                </a>

                {{-- Navigation Desktop --}}
                <nav class="hidden lg:flex items-center gap-8 text-white font-medium">
                    <a href="/" class="hover:text-amber-400 transition">Accueil</a>
                    <a href="{{ route('tenant.my.profil') }}" class="hover:text-amber-400 transition">Mon Profil</a>
                    @if (!auth('tenant')->user()->hasRole('directeur'))
                        <a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                            class="hover:text-amber-400 transition">Mon espace</a>
                        <a href="#contact" class="hover:text-amber-400 transition">Contact</a>
                    @endif
                    @auth('tenant')
                        @if (auth('tenant')->user()?->hasRole('directeur'))
                            <a href="{{ route('tenant.dashboard') }}"
                                class="hover:text-amber-400 transition">Administration</a>
                        @endif
                    @endauth

                </nav>

                {{-- Actions --}}
                <div class="flex items-center gap-4">
                    @guest('tenant')
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-flex items-center rounded-2xl bg-indigo-600 hover:bg-indigo-700 px-6 py-3 font-semibold text-white transition">
                            Se connecter
                        </a>
                    @endguest

                    @auth('tenant')
                        <div x-data="{ open: false }" class="relative hidden lg:block">
                            <button @click="open = !open"
                                class="flex items-center gap-3 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-2xl transition text-white">
                                <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                    class="h-9 w-9 rounded-full object-cover border border-white/30">
                                <span class="font-medium">{{ Auth::guard('tenant')->user()->name }}</span>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 mt-3 w-72 bg-white shadow-2xl py-2 text-slate-700 z-50">

                                <a href="{{ route('tenant.my.profil') }}" class="block px-6 py-3 hover:bg-slate-100">Mon
                                    profil</a>

                                @if (!auth('tenant')->user()->hasRole('directeur'))
                                    <a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                                        class="block px-6 py-3 hover:bg-slate-100">Mon espace</a>
                                @endif
                                <a href="{{ route('tenant.notifications.center') }}"
                                    class="block px-6 py-3 hover:bg-slate-100">Mes notifications</a>
                                <div class="border-t my-2"></div>
                                <button class="block w-full px-6 py-3 text-left text-red-600 hover:bg-red-50">Se
                                    deconnecter</button>
                            </div>
                        </div>
                    @endauth

                    {{-- Hamburger --}}
                    <button @click="mobileMenu = !mobileMenu"
                        class="lg:hidden w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-white/10 text-white z-50">
                        <div class="space-y-1.5">
                            <span class="block h-0.5 w-6 bg-white transition-all duration-300"
                                :class="{ 'rotate-45 translate-y-2': mobileMenu }"></span>
                            <span class="block h-0.5 w-6 bg-white transition-all duration-300"
                                :class="{ 'opacity-0': mobileMenu }"></span>
                            <span class="block h-0.5 w-6 bg-white transition-all duration-300"
                                :class="{ '-rotate-45 -translate-y-2': mobileMenu }"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- Menu Mobile Amélioré avec fermeture au clic extérieur --}}
        <div x-show="mobileMenu" x-transition @click.outside="mobileMenu = false"
            class="lg:hidden fixed inset-x-0 top-20 bg-sky-950 border-t border-white/10 shadow-2xl z-50 max-h-[calc(100vh-5rem)] overflow-y-auto">

            <nav class="p-6 space-y-2">
                <a href="/" @click="mobileMenu = false"
                    class="block px-5 py-4 rounded-2xl text-white hover:bg-white/10 transition font-medium">
                    Accueil
                </a>
                @if (!auth('tenant')->user()->hasRole('directeur'))
                    <a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                        @click="mobileMenu = false"
                        class="block px-5 py-4 rounded-2xl text-white hover:bg-white/10 transition font-medium">
                        Mon espace
                    </a>
                @endif
                <a href="#" @click="mobileMenu = false"
                    class="block px-5 py-4 rounded-2xl text-white hover:bg-white/10 transition font-medium">
                    Mon profil
                </a>
                @auth('tenant')
                    @if (auth('tenant')->user()?->hasRole('directeur'))
                        <a href="{{ route('tenant.dashboard') }}" @click="mobileMenu = false"
                            class="block px-5 py-4 rounded-2xl text-white hover:bg-white/10 transition font-medium">
                            Administration
                        </a>
                    @endif
                @endauth

                @guest('tenant')
                    <a href="{{ route('login') }}" @click="mobileMenu = false"
                        class="mt-6 block text-center bg-indigo-600 hover:bg-indigo-700 py-4 rounded-2xl font-semibold text-white">
                        Se connecter
                    </a>
                @endguest

                @auth('tenant')
                    <div class="pt-8 border-t border-white/10">
                        <div class="flex gap-4 px-5 mb-6">
                            <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}"
                                class="h-14 w-14 rounded-2xl object-cover">
                            <div class="text-white">
                                <p class="font-semibold">{{ Auth::guard('tenant')->user()->name }}</p>
                                <p class="text-sky-300 text-sm">{{ Auth::guard('tenant')->user()->email }}</p>
                            </div>
                        </div>
                        @if (!auth('tenant')->user()->hasRole('directeur'))
                            <a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                                class="block px-5 py-4 rounded-2xl hover:bg-white/10 text-white">Mon espace</a>
                        @else
                            <a href="{{ route('tenant.dashboard') }}"
                                class="block px-5 py-4 rounded-2xl hover:bg-white/10 text-white">Administration</a>
                        @endif
                        <a href="{{ route('tenant.my.profil') }}"
                            class="block px-5 py-4 rounded-2xl hover:bg-white/10 text-white">Mon profil</a>
                        <a href="{{ route('tenant.notifications.center') }}"
                            class="block px-5 py-4 rounded-2xl hover:bg-white/10 text-white">Mes notifications</a>
                        <button
                            class="mt-6 w-full py-4 text-red-400 hover:bg-red-800/60 rounded-2xl transition">Déconnexion</button>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop');">
        </div>

        <div class="absolute inset-0 bg-gradient-to-r from-sky-950/90 via-black/70 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/40"></div>

        <div class="relative max-w-7xl mx-auto px-6 pt-24 pb-20">
            <div class="max-w-3xl">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-5 py-2 text-sm text-white backdrop-blur-md">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Bienvenue à {{ tenant()?->school_name }}
                </span>

                <h1 class="mt-8 text-5xl sm:text-6xl lg:text-7xl font-black text-white leading-none tracking-tighter">
                    Former les <span class="text-indigo-400">leaders</span><br>de demain, une passion et un devoir !
                </h1>

                <p class="mt-6 text-lg sm:text-xl text-slate-200 max-w-xl font-mono">
                    Une éducation d'excellence qui allie discipline, innovation et valeurs humaines.
                </p>

                <div class="mt-10 flex flex-wrap gap-4 font-mono">
                    @guest('tenant')
                        <a href="{{ route('login') }}"
                            class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-semibold text-white shadow-xl transition">
                            Me connecter
                        </a>
                    @else
                        @if (auth('tenant')->user()->hasRole('directeur'))
                            <a href="{{ route('tenant.dashboard') }}"
                                class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-semibold text-white shadow-xl transition">
                                Accéder à mon espace administrateur
                            </a>
                        @else
                            <a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                                class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-semibold text-white shadow-xl transition">
                                Accéder à mon espace
                            </a>
                        @endif
                    @endguest
                    <a href="#contact"
                        class="px-8 py-4 border border-white/30 hover:bg-white/10 rounded-2xl font-semibold text-white backdrop-blur transition">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STATISTIQUES --}}
    <section class="relative z-20 -mt-12 md:-mt-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-400 rounded-3xl shadow-xl p-8 text-center hover:-translate-y-1 transition">
                    <div class="text-6xl mb-3">👨‍🎓</div>
                    <h3 class="text-5xl font-black text-indigo-600">1200+</h3>
                    <p class="text-slate-600 mt-1">Élèves</p>
                </div>
                <div class="bg-slate-400 rounded-3xl shadow-xl p-8 text-center hover:-translate-y-1 transition">
                    <div class="text-6xl mb-3">🏆</div>
                    <h3 class="text-5xl font-black text-indigo-600">98%</h3>
                    <p class="text-slate-600 mt-1">Taux de réussite</p>
                </div>
                <div class="bg-slate-400 rounded-3xl shadow-xl p-8 text-center hover:-translate-y-1 transition">
                    <div class="text-6xl mb-3">👨‍🏫</div>
                    <h3 class="text-5xl font-black text-indigo-600">80+</h3>
                    <p class="text-slate-600 mt-1">Enseignants</p>
                </div>
                <div class="bg-slate-400 rounded-3xl shadow-xl p-8 text-center hover:-translate-y-1 transition">
                    <div class="text-6xl mb-3">⭐</div>
                    <h3 class="text-5xl font-black text-indigo-600">25</h3>
                    <p class="text-slate-600 mt-1">Années d'excellence</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FILIÈRES --}}
    <section id="courses" class="py-24 bg-transparent">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-black">Nos Filières ou séries</h2>
                <p class="mt-4 text-slate-600 max-w-md mx-auto">
                    Des formations modernes adaptées aux défis du monde professionnel
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-400 rounded-3xl p-10 shadow hover:shadow-2xl transition group">
                    <div class="text-6xl mb-6">💻</div>
                    <h3 class="text-2xl font-bold mb-3">Informatique & Digital</h3>
                    <p class="text-slate-600">Développement web, intelligence artificielle, cybersécurité et data
                        science.</p>
                    <div class="mt-8 text-indigo-600 font-medium group-hover:translate-x-2 transition">En savoir plus →
                    </div>
                </div>

                <div class="bg-slate-400 rounded-3xl p-10 shadow hover:shadow-2xl transition group">
                    <div class="text-6xl mb-6">⚙️</div>
                    <h3 class="text-2xl font-bold mb-3">Génie Technique</h3>
                    <p class="text-slate-600">Électrotechnique, mécanique, génie civil et maintenance industrielle.</p>
                    <div class="mt-8 text-indigo-600 font-medium group-hover:translate-x-2 transition">En savoir plus →
                    </div>
                </div>

                <div class="bg-slate-400 rounded-3xl p-10 shadow hover:shadow-2xl transition group">
                    <div class="text-6xl mb-6">📊</div>
                    <h3 class="text-2xl font-bold mb-3">Gestion & Management</h3>
                    <p class="text-slate-600">Finance, marketing, ressources humaines et entrepreneuriat.</p>
                    <div class="mt-8 text-indigo-600 font-medium group-hover:translate-x-2 transition">En savoir plus →
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- GALERIE --}}
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-center text-5xl font-black mb-16">Des images de nous à {{ tenant('school_name') }}</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @for ($i = 7; $i <= randomNumber(11, 18); $i++)
                    <div class="aspect-square overflow-hidden rounded-3xl shadow-lg group">
                        <img src="{{ asset('images/school' . $i . '.jpg') }}"
                            class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- TÉMOIGNAGES --}}
    <section class="py-24 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-center text-5xl font-black mb-16">Ce qu'ils disent de nous</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8">
                    <p class="italic text-lg">"Cette école m'a donné bien plus que des connaissances : elle m'a appris
                        à croire en moi."</p>
                    <div class="mt-8 flex items-center gap-3">
                        <div class="h-12 w-12 bg-slate-700 rounded-2xl"></div>
                        <div>
                            <p class="font-semibold">Jean Dupont</p>
                            <p class="text-sm text-slate-400">Promotion 2024 - Informatique</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8">
                    <p class="italic text-lg">"Les enseignants sont passionnés et disponibles. Une véritable famille."
                    </p>
                    <div class="mt-8 flex items-center gap-3">
                        <div class="h-12 w-12 bg-slate-700 rounded-2xl"></div>
                        <div>
                            <p class="font-semibold">Marie Konan</p>
                            <p class="text-sm text-slate-400">Promotion 2023 - Gestion</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8">
                    <p class="italic text-lg">"Grâce à cette formation, j'ai pu intégrer une grande entreprise dès ma
                        sortie."</p>
                    <div class="mt-8 flex items-center gap-3">
                        <div class="h-12 w-12 bg-slate-700 rounded-2xl"></div>
                        <div>
                            <p class="font-semibold">Alain Traoré</p>
                            <p class="text-sm text-slate-400">Promotion 2025 - Génie Technique</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-950 text-white py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-2xl">🎓
                        </div>
                        <h3 class="font-bold text-2xl text-gray-400">{{ tenant('school_name') }}</h3>
                    </div>
                    <p class="text-slate-600">{{ tenant('school_devise') }}</p>
                </div>

                @auth('tenant')
                    <div>
                        <h4 class="font-bold mb-6">Navigation</h4>
                        <ul class="space-y-3 text-slate-400">
                            <li><a href="/" class="hover:text-white">Accueil</a></li>
                            @if (!auth('tenant')->user()->hasRole('directeur'))
                                <li><a href="{{ (auth('tenant')->user()->hasRole('enseignant') ? route('tenant.my.teacher.space') : auth('tenant')->user()->hasRole('tuteur')) ? route('tenant.my.parent.space') : route('tenant.my.profil') }}"
                                        class="hover:text-white">Mon espace</a></li>
                            @else
                                <li><a href="{{ route('tenant.dashboard') }}" class="hover:text-white">Administration</a>
                                </li>
                            @endif

                            <li><a href="{{ route('tenant.my.profil') }}" class="hover:text-white">Mon profil</a></li>
                            <li><a href="{{ route('tenant.notifications.center') }}" class="hover:text-white">Mes
                                    notifications</a></li>
                        </ul>
                    </div>
                    @endif

                    <div>
                        <h4 class="font-bold mb-6">Contact</h4>
                        <ul class="space-y-3 text-slate-400">
                            <li>{{ tenant()?->adresse }}</li>
                            <li>+229 {{ tenant()?->contacts }}</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-6 text-slate-300">Notre vision</h4>
                        <p class="text-slate-600 italic font-semibold font-mono">
                            Promouvoir l'excellence, par la concrétisation des résultats de fin d'année et contribuer à
                            l'insertion active de nos apprenants dans la vie professionnelle pour le Développement national
                            à long terme!
                        </p>
                    </div>

                </div>

                <div class="border-t border-white/10 mt-16 pt-8 text-center text-slate-500 text-sm">
                    © {{ date('Y') }} {{ tenant()?->school_name }} - Tous droits réservés
                </div>
            </div>
        </footer>
    </div>

