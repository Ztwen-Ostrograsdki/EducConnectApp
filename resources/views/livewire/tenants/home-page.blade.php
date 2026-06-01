<div class="w-full overflow-x-hidden">
    <header x-data="{
        mobileMenu: false,
        userMenu: false,
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
    
            window.addEventListener('resize', () => {
    
                if (window.innerWidth >= 1024) {
                    this.mobileMenu = false;
                }
            });
        }
    }" :class="visible ? 'translate-y-0' : '-translate-y-full'" class="fixed inset-x-0 top-0 z-50 transform transition-all duration-300">

        {{-- Overlay Mobile --}}
        <div x-show="mobileMenu" x-transition.opacity @click="mobileMenu = false" class="fixed inset-0 bg-black/30 backdrop-blur-sm lg:hidden" style="display:none"></div>

        <div class="mx-auto w-full m-0 p-0">

            <div class="overflow-hidden border border-white/20 bg-white/80 shadow-2xl backdrop-blur-xl">

                <div class="flex h-20 items-center justify-between px-5">

                    {{-- Logo --}}
                    <a href="/" class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-xl text-white shadow-lg">
                            🎓
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-900">
                                École
                            </h2>

                            <p class="text-xs text-slate-500">
                                Excellence • Discipline
                            </p>
                        </div>
                    </a>

                    {{-- Navigation Desktop --}}
                    <nav class="hidden items-center gap-8 lg:flex">
                        <a href="#" class="font-medium transition hover:text-indigo-600">
                            Accueil
                        </a>

                        <a href="#about" class="font-medium transition hover:text-indigo-600">
                            À propos
                        </a>

                        <a href="#courses" class="font-medium transition hover:text-indigo-600">
                            Filières
                        </a>

                        <a href="#news" class="font-medium transition hover:text-indigo-600">
                            Actualités
                        </a>

                        <a href="#gallery" class="font-medium transition hover:text-indigo-600">
                            Galerie
                        </a>

                        <a href="#contact" class="font-medium transition hover:text-indigo-600">
                            Contact
                        </a>
                    </nav>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3">

                        @guest('tenant')
                            <a href="{{ route('login') }}" class="hidden rounded-xl bg-indigo-600 px-5 py-2.5 font-semibold text-white transition hover:bg-indigo-700 sm:inline-flex">
                                Connexion
                            </a>
                        @endguest

                        @auth('tenant')
                            <div x-data="{ open: false }" class="relative hidden lg:block">

                                <button @click="open = !open" class="flex items-center gap-3 rounded-2xl border border-slate-200 px-3 py-2 transition hover:bg-slate-50">

                                    <img src="{{ Auth::guard('tenant')->user()->profil_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('tenant')->user()->name) }}" class="h-9 w-9 rounded-full object-cover">

                                    <span class="font-medium">
                                        {{ Auth::guard('tenant')->user()->name }}
                                    </span>

                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition style="display:none" class="absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border bg-white shadow-2xl">

                                    <a href="#" class="block px-5 py-3 hover:bg-slate-50">
                                        Tableau de bord
                                    </a>

                                    <a href="#" class="block px-5 py-3 hover:bg-slate-50">
                                        Mon profil
                                    </a>

                                    <a href="#" class="block px-5 py-3 hover:bg-slate-50">
                                        Paramètres
                                    </a>

                                    <div class="border-t"></div>

                                    <button class="block w-full px-5 py-3 text-left text-red-600 hover:bg-red-50">
                                        Déconnexion
                                    </button>

                                </div>

                            </div>
                        @endauth

                        {{-- Hamburger --}}
                        <button @click="mobileMenu = !mobileMenu" class="relative flex h-11 w-11 items-center justify-center rounded-xl transition hover:bg-slate-100 lg:hidden">

                            <span class="absolute h-0.5 w-6 bg-slate-800 transition-all duration-300"
                                :class="mobileMenu
                                    ?
                                    'rotate-45' :
                                    '-translate-y-2'"></span>

                            <span class="absolute h-0.5 w-6 bg-slate-800 transition-all duration-300"
                                :class="mobileMenu
                                    ?
                                    'opacity-0' :
                                    'opacity-100'"></span>

                            <span class="absolute h-0.5 w-6 bg-slate-800 transition-all duration-300"
                                :class="mobileMenu
                                    ?
                                    '-rotate-45' :
                                    'translate-y-2'"></span>

                        </button>

                    </div>

                </div>

                {{-- Menu Mobile --}}
                <div x-show="mobileMenu" @click.outside="mobileMenu = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2" style="display:none" class="border-t bg-white lg:hidden">

                    <nav class="space-y-1 p-4">

                        @foreach (['Accueil' => '#', 'À propos' => '#about', 'Filières' => '#courses', 'Actualités' => '#news', 'Galerie' => '#gallery', 'Contact' => '#contact'] as $label => $url)
                            <a href="{{ $url }}" @click="mobileMenu = false" class="block rounded-xl px-4 py-3 font-medium transition hover:bg-slate-100">
                                {{ $label }}
                            </a>
                        @endforeach

                        @guest('tenant')
                            <div class="pt-4">

                                <a href="{{ route('login') }}" @click="mobileMenu = false" class="flex justify-center rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white">
                                    Connexion
                                </a>

                            </div>
                        @endguest

                        @auth('tenant')
                            <div class="mt-4 border-t pt-4">

                                <div class="mb-4 flex items-center gap-3">

                                    <img src="{{ Auth::guard('tenant')->user()->profil_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('tenant')->user()->name) }}" class="h-11 w-11 rounded-full">

                                    <div>
                                        <div class="font-semibold">
                                            {{ Auth::guard('tenant')->user()->name }}
                                        </div>

                                        <div class="text-sm text-slate-500">
                                            {{ Auth::guard('tenant')->user()->email }}
                                        </div>
                                    </div>

                                </div>

                                <a href="#" class="block rounded-xl px-4 py-3 hover:bg-slate-100">
                                    Tableau de bord
                                </a>

                                <a href="#" class="block rounded-xl px-4 py-3 hover:bg-slate-100">
                                    Mon profil
                                </a>

                                <a href="#" class="block rounded-xl px-4 py-3 hover:bg-slate-100">
                                    Paramètres
                                </a>

                                <button class="mt-2 block w-full rounded-xl px-4 py-3 text-left text-red-600 hover:bg-red-50">
                                    Déconnexion
                                </button>

                            </div>
                        @endauth

                    </nav>

                </div>

            </div>

        </div>

    </header>
    <section class="relative min-h-screen overflow-hidden">

        {{-- Image --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center scale-110 blur-[2px]" style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop');"></div>

            <div class="absolute inset-0 bg-black/60"></div>
        </div>

        {{-- Overlay dégradé --}}
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-950/80 via-black/50 to-transparent"></div>

        {{-- Contenu --}}
        <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-6">

            <div class="max-w-3xl">

                <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-white backdrop-blur">
                    Bienvenue à
                </span>

                <h1 class="mt-8 text-6xl font-black text-white leading-tight">
                    Former aujourd'hui
                    <span class="text-indigo-400">
                        les leaders
                    </span>
                    de demain
                </h1>

                <p class="mt-6 max-w-2xl text-xl text-slate-200">
                    Un environnement d'excellence pour révéler le potentiel
                    de chaque élève et construire un avenir meilleur.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">

                    <a href="#about" class="rounded-2xl bg-indigo-600 px-8 py-4 font-semibold text-white shadow-xl hover:bg-indigo-700">
                        Découvrir l'école
                    </a>

                    <a href="#contact" class="rounded-2xl border border-white/30 bg-white/10 px-8 py-4 font-semibold text-white backdrop-blur hover:bg-white/20">
                        Nous contacter
                    </a>

                </div>

            </div>

        </div>

    </section>
    <section class="-mt-20 relative z-20">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-4 gap-6">

                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <h3 class="text-5xl font-black text-indigo-600">
                        1200+
                    </h3>
                    <p>Élèves</p>
                </div>

                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <h3 class="text-5xl font-black text-indigo-600">
                        98%
                    </h3>
                    <p>Réussite</p>
                </div>

                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <h3 class="text-5xl font-black text-indigo-600">
                        80+
                    </h3>
                    <p>Enseignants</p>
                </div>

                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <h3 class="text-5xl font-black text-indigo-600">
                        25
                    </h3>
                    <p>Années d'expérience</p>
                </div>

            </div>

        </div>
    </section>
    <section id="courses" class="py-24">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center">
                <h2 class="text-5xl font-black">
                    Nos Filières
                </h2>

                <p class="mt-4 text-slate-500">
                    Une formation adaptée aux besoins du futur.
                </p>
            </div>

            <div class="mt-16 grid md:grid-cols-3 gap-8">

                <div class="rounded-3xl p-8 bg-white shadow-lg">
                    <div class="text-5xl">💻</div>
                    <h3 class="mt-6 text-2xl font-bold">
                        Informatique
                    </h3>
                </div>

                <div class="rounded-3xl p-8 bg-white shadow-lg">
                    <div class="text-5xl">⚙️</div>
                    <h3 class="mt-6 text-2xl font-bold">
                        Génie Technique
                    </h3>
                </div>

                <div class="rounded-3xl p-8 bg-white shadow-lg">
                    <div class="text-5xl">📊</div>
                    <h3 class="mt-6 text-2xl font-bold">
                        Gestion
                    </h3>
                </div>

            </div>

        </div>
    </section>
    <section id="gallery" class="py-24 bg-slate-50">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-center text-5xl font-black">
                Galerie
            </h2>

            <div class="grid md:grid-cols-4 gap-4 mt-16">

                @for ($i = 1; $i <= 8; $i++)
                    <div class="overflow-hidden rounded-3xl shadow-lg">
                        <img src="https://picsum.photos/600/400?random={{ $i }}" class="h-full w-full object-cover transition duration-500 hover:scale-110">
                    </div>
                @endfor

            </div>

        </div>

    </section>
    <section class="py-24">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-center text-5xl font-black">
                Témoignages
            </h2>

            <div class="grid md:grid-cols-3 gap-8 mt-16">

                <div class="bg-white p-8 rounded-3xl shadow-lg">
                    <p>
                        Une école exceptionnelle.
                    </p>

                    <div class="mt-6 font-bold">
                        Jean Dupont
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg">
                    <p>
                        Les enseignants sont excellents.
                    </p>

                    <div class="mt-6 font-bold">
                        Marie K.
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg">
                    <p>
                        Une très bonne préparation aux examens.
                    </p>

                    <div class="mt-6 font-bold">
                        Alain T.
                    </div>
                </div>

            </div>

        </div>

    </section>
    <footer class="bg-slate-950 text-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-4 gap-12">

                <div>
                    <h3 class="font-bold text-2xl">
                        École
                    </h3>

                    <p class="mt-4 text-slate-400">
                        Excellence • Discipline • Réussite
                    </p>
                </div>

                <div>
                    <h4 class="font-bold">
                        Navigation
                    </h4>
                </div>

                <div>
                    <h4 class="font-bold">
                        Contact
                    </h4>
                </div>

                <div>
                    <h4 class="font-bold">
                        Réseaux
                    </h4>
                </div>

            </div>

        </div>

    </footer>
</div>

