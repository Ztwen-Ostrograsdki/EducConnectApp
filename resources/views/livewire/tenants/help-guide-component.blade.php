<div class="w-full bg-slate-950 scroll-smooth" x-data @keydown.escape.window="$wire.modalSectionId && $wire.closeModal()">

    <div class="mx-auto w-full max-w-[1500px] p-3 sm:p-4 lg:p-5">

        {{-- ===================================================== --}}
        {{-- HERO — design moderne --}}
        {{-- ===================================================== --}}
        <section class="mb-8">
            <div
                class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950/40 p-6 md:p-10">

                {{-- décor --}}
                <div
                    class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-violet-500/10 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute right-1/3 top-1/2 h-32 w-32 rounded-full bg-cyan-500/10 blur-2xl">
                </div>
                <div
                    class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.04)_1px,transparent_1px)] bg-[size:32px_32px]">
                </div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p
                            class="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-400/20 bg-indigo-500/10 px-3 py-1 text-[11px] font-medium uppercase tracking-widest text-indigo-300">
                            <x-lucide-compass class="h-3.5 w-3.5" />
                            Guide de prise en main
                        </p>

                        <h1
                            class="text-3xl font-bold tracking-tight text-white md:text-4xl lg:text-[2.5rem] lg:leading-tight">
                            Mettre en route votre
                            <span
                                class="bg-gradient-to-r from-indigo-300 via-violet-300 to-cyan-300 bg-clip-text text-transparent">établissement</span>
                            sur EducConnect
                        </h1>

                        <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-400 md:text-[15px]">
                            Chaque donnée de la plateforme s'appuie sur la précédente.
                            Suivez les étapes dans l'ordre pour la mise en route, puis consultez
                            les bonnes pratiques au fil de l'année.
                        </p>
                    </div>

                    <div class="flex shrink-0 gap-3">
                        <div
                            class="min-w-[110px] rounded-2xl border border-indigo-500/20 bg-indigo-500/10 px-5 py-4 text-center backdrop-blur-sm transition-transform duration-300 hover:-translate-y-1">
                            <div class="text-2xl font-bold tabular-nums text-indigo-300">11</div>
                            <div class="mt-0.5 text-[11px] font-medium uppercase tracking-wide text-indigo-400/70">Mise
                                en route</div>
                        </div>
                        <div
                            class="min-w-[110px] rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-center backdrop-blur-sm transition-transform duration-300 hover:-translate-y-1">
                            <div class="text-2xl font-bold tabular-nums text-emerald-300">5</div>
                            <div class="mt-0.5 text-[11px] font-medium uppercase tracking-wide text-emerald-400/70">
                                Pratiques</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @php
            $colorMap = [
                'indigo' => [
                    'border' => 'border-indigo-500/25',
                    'borderHover' => 'hover:border-indigo-400/50',
                    'bg' => 'bg-indigo-500/5',
                    'bgIcon' => 'bg-indigo-500/15',
                    'text' => 'text-indigo-300',
                    'ring' => 'ring-indigo-500/20',
                    'shadow' => 'hover:shadow-indigo-500/10',
                    'accent' => 'from-indigo-500/20',
                    'btn' => 'text-indigo-300 hover:text-indigo-200',
                    'btnBg' => 'bg-indigo-500/10 border-indigo-500/25 hover:bg-indigo-500/20',
                    'navHover' => 'hover:bg-indigo-500/10 hover:text-indigo-200',
                    'navIconHover' =>
                        'group-hover:border-indigo-500/40 group-hover:bg-indigo-500/15 group-hover:text-indigo-300',
                    'chip' =>
                        'border-indigo-500/25 bg-indigo-500/10 text-indigo-300 hover:bg-indigo-500/20 hover:border-indigo-500/40',
                ],
                'violet' => [
                    'border' => 'border-violet-500/25',
                    'borderHover' => 'hover:border-violet-400/50',
                    'bg' => 'bg-violet-500/5',
                    'bgIcon' => 'bg-violet-500/15',
                    'text' => 'text-violet-300',
                    'ring' => 'ring-violet-500/20',
                    'shadow' => 'hover:shadow-violet-500/10',
                    'accent' => 'from-violet-500/20',
                    'btn' => 'text-violet-300 hover:text-violet-200',
                    'btnBg' => 'bg-violet-500/10 border-violet-500/25 hover:bg-violet-500/20',
                    'navHover' => 'hover:bg-violet-500/10 hover:text-violet-200',
                    'navIconHover' =>
                        'group-hover:border-violet-500/40 group-hover:bg-violet-500/15 group-hover:text-violet-300',
                    'chip' =>
                        'border-violet-500/25 bg-violet-500/10 text-violet-300 hover:bg-violet-500/20 hover:border-violet-500/40',
                ],
                'fuchsia' => [
                    'border' => 'border-fuchsia-500/25',
                    'borderHover' => 'hover:border-fuchsia-400/50',
                    'bg' => 'bg-fuchsia-500/5',
                    'bgIcon' => 'bg-fuchsia-500/15',
                    'text' => 'text-fuchsia-300',
                    'ring' => 'ring-fuchsia-500/20',
                    'shadow' => 'hover:shadow-fuchsia-500/10',
                    'accent' => 'from-fuchsia-500/20',
                    'btn' => 'text-fuchsia-300 hover:text-fuchsia-200',
                    'btnBg' => 'bg-fuchsia-500/10 border-fuchsia-500/25 hover:bg-fuchsia-500/20',
                    'navHover' => 'hover:bg-fuchsia-500/10 hover:text-fuchsia-200',
                    'navIconHover' =>
                        'group-hover:border-fuchsia-500/40 group-hover:bg-fuchsia-500/15 group-hover:text-fuchsia-300',
                    'chip' =>
                        'border-fuchsia-500/25 bg-fuchsia-500/10 text-fuchsia-300 hover:bg-fuchsia-500/20 hover:border-fuchsia-500/40',
                ],
                'rose' => [
                    'border' => 'border-rose-500/25',
                    'borderHover' => 'hover:border-rose-400/50',
                    'bg' => 'bg-rose-500/5',
                    'bgIcon' => 'bg-rose-500/15',
                    'text' => 'text-rose-300',
                    'ring' => 'ring-rose-500/20',
                    'shadow' => 'hover:shadow-rose-500/10',
                    'accent' => 'from-rose-500/20',
                    'btn' => 'text-rose-300 hover:text-rose-200',
                    'btnBg' => 'bg-rose-500/10 border-rose-500/25 hover:bg-rose-500/20',
                    'navHover' => 'hover:bg-rose-500/10 hover:text-rose-200',
                    'navIconHover' =>
                        'group-hover:border-rose-500/40 group-hover:bg-rose-500/15 group-hover:text-rose-300',
                    'chip' =>
                        'border-rose-500/25 bg-rose-500/10 text-rose-300 hover:bg-rose-500/20 hover:border-rose-500/40',
                ],
                'orange' => [
                    'border' => 'border-orange-500/25',
                    'borderHover' => 'hover:border-orange-400/50',
                    'bg' => 'bg-orange-500/5',
                    'bgIcon' => 'bg-orange-500/15',
                    'text' => 'text-orange-300',
                    'ring' => 'ring-orange-500/20',
                    'shadow' => 'hover:shadow-orange-500/10',
                    'accent' => 'from-orange-500/20',
                    'btn' => 'text-orange-300 hover:text-orange-200',
                    'btnBg' => 'bg-orange-500/10 border-orange-500/25 hover:bg-orange-500/20',
                    'navHover' => 'hover:bg-orange-500/10 hover:text-orange-200',
                    'navIconHover' =>
                        'group-hover:border-orange-500/40 group-hover:bg-orange-500/15 group-hover:text-orange-300',
                    'chip' =>
                        'border-orange-500/25 bg-orange-500/10 text-orange-300 hover:bg-orange-500/20 hover:border-orange-500/40',
                ],
                'amber' => [
                    'border' => 'border-amber-500/25',
                    'borderHover' => 'hover:border-amber-400/50',
                    'bg' => 'bg-amber-500/5',
                    'bgIcon' => 'bg-amber-500/15',
                    'text' => 'text-amber-300',
                    'ring' => 'ring-amber-500/20',
                    'shadow' => 'hover:shadow-amber-500/10',
                    'accent' => 'from-amber-500/20',
                    'btn' => 'text-amber-300 hover:text-amber-200',
                    'btnBg' => 'bg-amber-500/10 border-amber-500/25 hover:bg-amber-500/20',
                    'navHover' => 'hover:bg-amber-500/10 hover:text-amber-200',
                    'navIconHover' =>
                        'group-hover:border-amber-500/40 group-hover:bg-amber-500/15 group-hover:text-amber-300',
                    'chip' =>
                        'border-amber-500/25 bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 hover:border-amber-500/40',
                ],
                'lime' => [
                    'border' => 'border-lime-500/25',
                    'borderHover' => 'hover:border-lime-400/50',
                    'bg' => 'bg-lime-500/5',
                    'bgIcon' => 'bg-lime-500/15',
                    'text' => 'text-lime-300',
                    'ring' => 'ring-lime-500/20',
                    'shadow' => 'hover:shadow-lime-500/10',
                    'accent' => 'from-lime-500/20',
                    'btn' => 'text-lime-300 hover:text-lime-200',
                    'btnBg' => 'bg-lime-500/10 border-lime-500/25 hover:bg-lime-500/20',
                    'navHover' => 'hover:bg-lime-500/10 hover:text-lime-200',
                    'navIconHover' =>
                        'group-hover:border-lime-500/40 group-hover:bg-lime-500/15 group-hover:text-lime-300',
                    'chip' =>
                        'border-lime-500/25 bg-lime-500/10 text-lime-300 hover:bg-lime-500/20 hover:border-lime-500/40',
                ],
                'emerald' => [
                    'border' => 'border-emerald-500/25',
                    'borderHover' => 'hover:border-emerald-400/50',
                    'bg' => 'bg-emerald-500/5',
                    'bgIcon' => 'bg-emerald-500/15',
                    'text' => 'text-emerald-300',
                    'ring' => 'ring-emerald-500/20',
                    'shadow' => 'hover:shadow-emerald-500/10',
                    'accent' => 'from-emerald-500/20',
                    'btn' => 'text-emerald-300 hover:text-emerald-200',
                    'btnBg' => 'bg-emerald-500/10 border-emerald-500/25 hover:bg-emerald-500/20',
                    'navHover' => 'hover:bg-emerald-500/10 hover:text-emerald-200',
                    'navIconHover' =>
                        'group-hover:border-emerald-500/40 group-hover:bg-emerald-500/15 group-hover:text-emerald-300',
                    'chip' =>
                        'border-emerald-500/25 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20 hover:border-emerald-500/40',
                ],
                'teal' => [
                    'border' => 'border-teal-500/25',
                    'borderHover' => 'hover:border-teal-400/50',
                    'bg' => 'bg-teal-500/5',
                    'bgIcon' => 'bg-teal-500/15',
                    'text' => 'text-teal-300',
                    'ring' => 'ring-teal-500/20',
                    'shadow' => 'hover:shadow-teal-500/10',
                    'accent' => 'from-teal-500/20',
                    'btn' => 'text-teal-300 hover:text-teal-200',
                    'btnBg' => 'bg-teal-500/10 border-teal-500/25 hover:bg-teal-500/20',
                    'navHover' => 'hover:bg-teal-500/10 hover:text-teal-200',
                    'navIconHover' =>
                        'group-hover:border-teal-500/40 group-hover:bg-teal-500/15 group-hover:text-teal-300',
                    'chip' =>
                        'border-teal-500/25 bg-teal-500/10 text-teal-300 hover:bg-teal-500/20 hover:border-teal-500/40',
                ],
                'cyan' => [
                    'border' => 'border-cyan-500/25',
                    'borderHover' => 'hover:border-cyan-400/50',
                    'bg' => 'bg-cyan-500/5',
                    'bgIcon' => 'bg-cyan-500/15',
                    'text' => 'text-cyan-300',
                    'ring' => 'ring-cyan-500/20',
                    'shadow' => 'hover:shadow-cyan-500/10',
                    'accent' => 'from-cyan-500/20',
                    'btn' => 'text-cyan-300 hover:text-cyan-200',
                    'btnBg' => 'bg-cyan-500/10 border-cyan-500/25 hover:bg-cyan-500/20',
                    'navHover' => 'hover:bg-cyan-500/10 hover:text-cyan-200',
                    'navIconHover' =>
                        'group-hover:border-cyan-500/40 group-hover:bg-cyan-500/15 group-hover:text-cyan-300',
                    'chip' =>
                        'border-cyan-500/25 bg-cyan-500/10 text-cyan-300 hover:bg-cyan-500/20 hover:border-cyan-500/40',
                ],
                'sky' => [
                    'border' => 'border-sky-500/25',
                    'borderHover' => 'hover:border-sky-400/50',
                    'bg' => 'bg-sky-500/5',
                    'bgIcon' => 'bg-sky-500/15',
                    'text' => 'text-sky-300',
                    'ring' => 'ring-sky-500/20',
                    'shadow' => 'hover:shadow-sky-500/10',
                    'accent' => 'from-sky-500/20',
                    'btn' => 'text-sky-300 hover:text-sky-200',
                    'btnBg' => 'bg-sky-500/10 border-sky-500/25 hover:bg-sky-500/20',
                    'navHover' => 'hover:bg-sky-500/10 hover:text-sky-200',
                    'navIconHover' =>
                        'group-hover:border-sky-500/40 group-hover:bg-sky-500/15 group-hover:text-sky-300',
                    'chip' =>
                        'border-sky-500/25 bg-sky-500/10 text-sky-300 hover:bg-sky-500/20 hover:border-sky-500/40',
                ],
                'blue' => [
                    'border' => 'border-blue-500/25',
                    'borderHover' => 'hover:border-blue-400/50',
                    'bg' => 'bg-blue-500/5',
                    'bgIcon' => 'bg-blue-500/15',
                    'text' => 'text-blue-300',
                    'ring' => 'ring-blue-500/20',
                    'shadow' => 'hover:shadow-blue-500/10',
                    'accent' => 'from-blue-500/20',
                    'btn' => 'text-blue-300 hover:text-blue-200',
                    'btnBg' => 'bg-blue-500/10 border-blue-500/25 hover:bg-blue-500/20',
                    'navHover' => 'hover:bg-blue-500/10 hover:text-blue-200',
                    'navIconHover' =>
                        'group-hover:border-blue-500/40 group-hover:bg-blue-500/15 group-hover:text-blue-300',
                    'chip' =>
                        'border-blue-500/25 bg-blue-500/10 text-blue-300 hover:bg-blue-500/20 hover:border-blue-500/40',
                ],
                'purple' => [
                    'border' => 'border-purple-500/25',
                    'borderHover' => 'hover:border-purple-400/50',
                    'bg' => 'bg-purple-500/5',
                    'bgIcon' => 'bg-purple-500/15',
                    'text' => 'text-purple-300',
                    'ring' => 'ring-purple-500/20',
                    'shadow' => 'hover:shadow-purple-500/10',
                    'accent' => 'from-purple-500/20',
                    'btn' => 'text-purple-300 hover:text-purple-200',
                    'btnBg' => 'bg-purple-500/10 border-purple-500/25 hover:bg-purple-500/20',
                    'navHover' => 'hover:bg-purple-500/10 hover:text-purple-200',
                    'navIconHover' =>
                        'group-hover:border-purple-500/40 group-hover:bg-purple-500/15 group-hover:text-purple-300',
                    'chip' =>
                        'border-purple-500/25 bg-purple-500/10 text-purple-300 hover:bg-purple-500/20 hover:border-purple-500/40',
                ],
                'pink' => [
                    'border' => 'border-pink-500/25',
                    'borderHover' => 'hover:border-pink-400/50',
                    'bg' => 'bg-pink-500/5',
                    'bgIcon' => 'bg-pink-500/15',
                    'text' => 'text-pink-300',
                    'ring' => 'ring-pink-500/20',
                    'shadow' => 'hover:shadow-pink-500/10',
                    'accent' => 'from-pink-500/20',
                    'btn' => 'text-pink-300 hover:text-pink-200',
                    'btnBg' => 'bg-pink-500/10 border-pink-500/25 hover:bg-pink-500/20',
                    'navHover' => 'hover:bg-pink-500/10 hover:text-pink-200',
                    'navIconHover' =>
                        'group-hover:border-pink-500/40 group-hover:bg-pink-500/15 group-hover:text-pink-300',
                    'chip' =>
                        'border-pink-500/25 bg-pink-500/10 text-pink-300 hover:bg-pink-500/20 hover:border-pink-500/40',
                ],
                'red' => [
                    'border' => 'border-red-500/25',
                    'borderHover' => 'hover:border-red-400/50',
                    'bg' => 'bg-red-500/5',
                    'bgIcon' => 'bg-red-500/15',
                    'text' => 'text-red-300',
                    'ring' => 'ring-red-500/20',
                    'shadow' => 'hover:shadow-red-500/10',
                    'accent' => 'from-red-500/20',
                    'btn' => 'text-red-300 hover:text-red-200',
                    'btnBg' => 'bg-red-500/10 border-red-500/25 hover:bg-red-500/20',
                    'navHover' => 'hover:bg-red-500/10 hover:text-red-200',
                    'navIconHover' =>
                        'group-hover:border-red-500/40 group-hover:bg-red-500/15 group-hover:text-red-300',
                    'chip' =>
                        'border-red-500/25 bg-red-500/10 text-red-300 hover:bg-red-500/20 hover:border-red-500/40',
                ],
                'yellow' => [
                    'border' => 'border-yellow-500/25',
                    'borderHover' => 'hover:border-yellow-400/50',
                    'bg' => 'bg-yellow-500/5',
                    'bgIcon' => 'bg-yellow-500/15',
                    'text' => 'text-yellow-300',
                    'ring' => 'ring-yellow-500/20',
                    'shadow' => 'hover:shadow-yellow-500/10',
                    'accent' => 'from-yellow-500/20',
                    'btn' => 'text-yellow-300 hover:text-yellow-200',
                    'btnBg' => 'bg-yellow-500/10 border-yellow-500/25 hover:bg-yellow-500/20',
                    'navHover' => 'hover:bg-yellow-500/10 hover:text-yellow-200',
                    'navIconHover' =>
                        'group-hover:border-yellow-500/40 group-hover:bg-yellow-500/15 group-hover:text-yellow-300',
                    'chip' =>
                        'border-yellow-500/25 bg-yellow-500/10 text-yellow-300 hover:bg-yellow-500/20 hover:border-yellow-500/40',
                ],
            ];
            $badgeStyles = [
                'obligatoire' => 'bg-rose-500/15 text-rose-300 border-rose-500/25',
                'recommande' => 'bg-amber-500/15 text-amber-300 border-amber-500/25',
                'optionnel' => 'bg-sky-500/15 text-sky-300 border-sky-500/25',
                'avance' => 'bg-violet-500/15 text-violet-300 border-violet-500/25',
            ];
            $badgeLabels = [
                'obligatoire' => 'Obligatoire',
                'recommande' => 'Recommandé',
                'optionnel' => 'Optionnel',
                'avance' => 'Avancé',
            ];
        @endphp

        {{-- ===================================================== --}}
        {{-- MOBILE TOC --}}
        {{-- ===================================================== --}}
        <section class="mb-6 lg:hidden">
            <div class="flex gap-2 overflow-x-auto pb-2">
                @foreach ($this->sections as $section)
                    @php $cm = $colorMap[$section['color'] ?? 'indigo'] ?? $colorMap['indigo']; @endphp
                    <a href="#{{ $section['id'] }}"
                        class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-medium transition-all duration-200 active:scale-95 whitespace-nowrap {{ $cm['chip'] }}">
                        <x-dynamic-component :component="'lucide-' . $section['icon']" class="h-3.5 w-3.5" />
                        {{ $section['title'] }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- MAIN : sidebar sticky (fixe seulement quand le scroll la masquerait) --}}
        {{-- ===================================================== --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start">

            {{--
                SIDEBAR desktop — sticky intelligent :
                1. Reste dans le flux sous le hero (ne masque ni header ni hero)
                2. Passe en fixed uniquement quand le scroll la ferait sortir du viewport
                3. Revient dans le flux en remontant
            --}}
            <aside class="relative hidden w-[250px] shrink-0 self-start lg:block" x-data="{
                isFixed: false,
                topOffset: 16,
                naturalTop: 0,
            
                measure() {
                    const panel = this.$refs.panel;
                    if (!panel || this.isFixed) return;
                    this.naturalTop = window.scrollY + panel.getBoundingClientRect().top;
                },
            
                pin() {
                    const panel = this.$refs.panel;
                    const spacer = this.$refs.spacer;
                    if (!panel || !spacer || this.isFixed) return;
            
                    // 1. Mesurer pendant que le panneau est encore dans le flux
                    const r = panel.getBoundingClientRect();
                    const h = panel.offsetHeight;
            
                    // 2. Tout appliquer en synchrone (même frame) pour éviter le flash
                    spacer.style.height = h + 'px';
                    panel.style.position = 'fixed';
                    panel.style.top = this.topOffset + 'px';
                    panel.style.left = r.left + 'px';
                    panel.style.width = r.width + 'px';
                    panel.style.right = 'auto';
            
                    this.isFixed = true;
                },
            
                unpin() {
                    const panel = this.$refs.panel;
                    const spacer = this.$refs.spacer;
                    if (!panel || !spacer || !this.isFixed) return;
            
                    // Tout retirer en synchrone
                    panel.style.position = '';
                    panel.style.top = '';
                    panel.style.left = '';
                    panel.style.width = '';
                    panel.style.right = '';
                    spacer.style.height = '0px';
            
                    this.isFixed = false;
                    // Recalcule la position naturelle après retour dans le flux
                    requestAnimationFrame(() => this.measure());
                },
            
                onScroll() {
                    if (!this.$refs.panel) return;
                    const shouldFix = window.scrollY + this.topOffset >= this.naturalTop;
                    if (shouldFix) this.pin();
                    else this.unpin();
                },
            
                onResize() {
                    const panel = this.$refs.panel;
                    const spacer = this.$refs.spacer;
                    if (!panel || !spacer) return;
            
                    if (this.isFixed) {
                        const r = spacer.getBoundingClientRect();
                        panel.style.left = r.left + 'px';
                        panel.style.width = r.width + 'px';
                    } else {
                        this.measure();
                        this.onScroll();
                    }
                }
            }"
                x-init="$nextTick(() => {
                    measure();
                    onScroll();
                })" @scroll.window.passive="onScroll()" @resize.window.passive="onResize()">

                {{-- Spacer : hauteur gérée en JS (synchrone avec pin/unpin) --}}
                <div x-ref="spacer" class="w-full" style="height:0px" aria-hidden="true"></div>

                {{-- Panneau : relative par défaut ; position fixed appliquée en JS --}}
                <div x-ref="panel"
                    class="relative z-20 w-full flex max-h-[calc(100vh-2rem)] flex-col overflow-y-auto overscroll-contain rounded-2xl border border-slate-800/80 bg-slate-900/95 p-3 shadow-xl shadow-black/20 backdrop-blur-md">

                    <p class="mb-2 px-2.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                        Mise en route
                    </p>
                    <nav class="mb-5 flex flex-col gap-0.5">
                        @foreach ($this->sections as $section)
                            @continue($section['order'] > 10)
                            @php $cm = $colorMap[$section['color'] ?? 'indigo'] ?? $colorMap['indigo']; @endphp
                            <a href="#{{ $section['id'] }}"
                                class="group flex items-center gap-2.5 rounded-xl px-2.5 py-2 text-sm text-slate-400 transition-all duration-200 {{ $cm['navHover'] }}">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-slate-700/80 bg-slate-950 text-[10px] font-mono font-semibold text-slate-500 transition-all duration-200 {{ $cm['navIconHover'] }}">
                                    {{ $section['order'] }}
                                </span>
                                <span class="truncate font-medium">{{ $section['title'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <p class="mb-2 px-2.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                        Au quotidien
                    </p>
                    <nav class="flex flex-col gap-0.5">
                        @foreach ($this->sections as $section)
                            @continue($section['order'] <= 10)
                            @php $cm = $colorMap[$section['color'] ?? 'indigo'] ?? $colorMap['indigo']; @endphp
                            <a href="#{{ $section['id'] }}"
                                class="group flex items-center gap-2.5 rounded-xl px-2.5 py-2 text-sm text-slate-400 transition-all duration-200 {{ $cm['navHover'] }}">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg {{ $cm['bgIcon'] }} {{ $cm['text'] }} transition-all duration-200">
                                    <x-dynamic-component :component="'lucide-' . $section['icon']" class="h-3.5 w-3.5" />
                                </span>
                                <span class="truncate font-medium">{{ $section['title'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- CONTENU --}}
            <div class="min-w-0 flex-1 space-y-12">

                {{-- ========== MISE EN ROUTE ========== --}}
                <section>
                    <header class="mb-6">
                        <p class="mb-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-400/80">
                            Étapes 0 → 10
                        </p>
                        <h2 class="text-xl font-bold tracking-tight text-white md:text-2xl">
                            Mise en route
                        </h2>
                        <p class="mt-1.5 max-w-lg text-sm leading-relaxed text-slate-500">
                            À suivre dans l'ordre — chaque étape prépare la suivante.
                        </p>
                    </header>

                    <div class="space-y-4">
                        @foreach ($this->sections as $section)
                            @continue($section['order'] > 10)
                            @php
                                $c = $section['color'] ?? 'indigo';
                                $cm = $colorMap[$c] ?? $colorMap['indigo'];
                            @endphp

                            <article id="{{ $section['id'] }}"
                                class="scroll-mt-6 group relative overflow-hidden rounded-2xl border {{ $cm['border'] }} {{ $cm['bg'] }} bg-slate-900/60 backdrop-blur-sm transition-all duration-300 {{ $cm['borderHover'] }} hover:shadow-xl {{ $cm['shadow'] }}">

                                {{-- accent bar --}}
                                <div
                                    class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b {{ $cm['accent'] }} to-transparent opacity-80">
                                </div>

                                <div class="relative flex flex-col gap-5 p-5 sm:flex-row sm:gap-6 sm:p-6">

                                    {{-- numéro --}}
                                    <div class="flex shrink-0 items-center gap-3 sm:flex-col sm:items-start">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl border {{ $cm['border'] }} {{ $cm['bgIcon'] }} font-mono text-lg font-bold {{ $cm['text'] }} transition-transform duration-300 group-hover:scale-105">
                                            {{ $section['order'] }}
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        {{-- titre + badge --}}
                                        <div class="mb-2 flex flex-wrap items-center gap-2.5">
                                            <x-dynamic-component :component="'lucide-' . $section['icon']"
                                                class="hidden h-4 w-4 sm:inline-block {{ $cm['text'] }}" />
                                            <h3 class="text-base font-semibold tracking-tight text-white md:text-lg">
                                                {{ $section['title'] }}
                                            </h3>
                                            <span
                                                class="rounded-full border px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $badgeStyles[$section['badge']] ?? '' }}">
                                                {{ $badgeLabels[$section['badge']] ?? '' }}
                                            </span>
                                        </div>

                                        {{-- intro distinguée --}}
                                        <p
                                            class="mb-4 border-l-2 {{ $cm['border'] }} pl-3 text-sm leading-relaxed text-slate-400 italic">
                                            {{ $section['intro'] }}
                                        </p>

                                        {{-- points --}}
                                        <ul class="mb-5 space-y-2.5">
                                            @foreach ($section['points'] as $point)
                                                <li class="flex gap-2.5 text-sm leading-relaxed text-slate-300">
                                                    <x-lucide-check class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                                    <span>
                                                        @if (is_array($point))
                                                            <span
                                                                class="font-semibold text-slate-100">{{ $point['label'] }}</span>
                                                            <span class="text-slate-500"> — </span>
                                                            {{ $point['text'] }}
                                                        @else
                                                            {{ $point }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{-- actions --}}
                                        <div class="flex flex-wrap items-center gap-2.5">
                                            <a wire:navigate href="{{ route($section['route']) }}"
                                                class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 text-sm font-medium transition-all duration-200 active:scale-95 {{ $cm['btnBg'] }} {{ $cm['btn'] }}">
                                                {{ $section['cta'] }}
                                                <x-lucide-arrow-right class="h-3.5 w-3.5" />
                                            </a>
                                            <button type="button" wire:click="openModal('{{ $section['id'] }}')"
                                                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700/80 bg-slate-800/50 px-3.5 py-2 text-sm font-medium text-slate-300 transition-all duration-200 hover:border-slate-600 hover:bg-slate-800 hover:text-white active:scale-95">
                                                <x-lucide-maximize-2 class="h-3.5 w-3.5" />
                                                Lire de près
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                {{-- ========== AU QUOTIDIEN ========== --}}
                <section>
                    <header class="mb-6">
                        <p class="mb-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-400/80">
                            Bonnes pratiques
                        </p>
                        <h2 class="text-xl font-bold tracking-tight text-white md:text-2xl">
                            Au quotidien
                        </h2>
                        <p class="mt-1.5 max-w-lg text-sm leading-relaxed text-slate-500">
                            À consulter au besoin — indépendamment de l'ordre de mise en route.
                        </p>
                    </header>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($this->sections as $section)
                            @continue($section['order'] <= 10)
                            @php
                                $c = $section['color'] ?? 'indigo';
                                $cm = $colorMap[$c] ?? $colorMap['indigo'];
                            @endphp

                            <article id="{{ $section['id'] }}"
                                class="scroll-mt-6 group relative flex flex-col overflow-hidden rounded-2xl border {{ $cm['border'] }} {{ $cm['bg'] }} bg-slate-900/60 p-5 backdrop-blur-sm transition-all duration-300 {{ $cm['borderHover'] }} hover:-translate-y-0.5 hover:shadow-xl {{ $cm['shadow'] }}">

                                <div
                                    class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent">
                                </div>

                                <div class="mb-4 flex items-start gap-3">
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $cm['border'] }} {{ $cm['bgIcon'] }} {{ $cm['text'] }} transition-transform duration-300 group-hover:scale-105">
                                        <x-dynamic-component :component="'lucide-' . $section['icon']" class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-base font-semibold tracking-tight text-white">
                                                {{ $section['title'] }}
                                            </h3>
                                            <span
                                                class="rounded-full border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $badgeStyles[$section['badge']] ?? '' }}">
                                                {{ $badgeLabels[$section['badge']] ?? '' }}
                                            </span>
                                        </div>
                                        <p class="mt-1.5 text-sm leading-relaxed text-slate-400 italic">
                                            {{ $section['intro'] }}
                                        </p>
                                    </div>
                                </div>

                                <ul class="mb-5 flex-1 space-y-2">
                                    @foreach ($section['points'] as $point)
                                        <li class="flex gap-2 text-sm leading-relaxed text-slate-300">
                                            <x-lucide-check class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" />
                                            <span>
                                                @if (is_array($point))
                                                    <span
                                                        class="font-semibold text-slate-100">{{ $point['label'] }}</span>
                                                    <span class="text-slate-500"> — </span>
                                                    {{ $point['text'] }}
                                                @else
                                                    {{ $point }}
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-auto flex flex-wrap items-center gap-2">
                                    <a wire:navigate href="{{ route($section['route']) }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-sm font-medium transition-all duration-200 active:scale-95 {{ $cm['btnBg'] }} {{ $cm['btn'] }}">
                                        {{ $section['cta'] }}
                                        <x-lucide-arrow-right class="h-3.5 w-3.5" />
                                    </a>
                                    <button type="button" wire:click="openModal('{{ $section['id'] }}')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700/80 bg-slate-800/50 px-3 py-1.5 text-sm font-medium text-slate-300 transition-all duration-200 hover:border-slate-600 hover:bg-slate-800 hover:text-white active:scale-95">
                                        <x-lucide-maximize-2 class="h-3.5 w-3.5" />
                                        Lire de près
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- MODAL — fermeture au clic extérieur corrigée --}}
    {{-- ===================================================== --}}
    @if ($this->modalSection)
        @php
            $section = $this->modalSection;
            $c = $section['color'] ?? 'indigo';
            $cm = $colorMap[$c] ?? $colorMap['indigo'];
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data="{ show: true }"
            x-show="show" x-cloak x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" role="dialog" aria-modal="true"
            aria-labelledby="modal-title-{{ $section['id'] }}">

            {{-- Backdrop : clic ici ferme le modal --}}
            <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-sm" wire:click="closeModal"
                x-on:click="show = false"></div>

            {{-- Panel --}}
            <div class="relative z-10 flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 shadow-2xl shadow-black/40"
                x-show="show" x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.stop>

                {{-- Header --}}
                <div
                    class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-800 bg-slate-900/95 px-5 py-5 sm:px-6">
                    <div class="flex min-w-0 items-start gap-3">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $cm['border'] }} {{ $cm['bgIcon'] }} {{ $cm['text'] }}">
                            <x-dynamic-component :component="'lucide-' . $section['icon']" class="h-5 w-5" />
                        </div>
                        <div class="min-w-0">
                            <div class="mb-1 flex flex-wrap items-center gap-2">
                                <h2 id="modal-title-{{ $section['id'] }}"
                                    class="text-lg font-bold tracking-tight text-white">
                                    {{ $section['title'] }}
                                </h2>
                                <span
                                    class="rounded-full border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $badgeStyles[$section['badge']] ?? '' }}">
                                    {{ $badgeLabels[$section['badge']] ?? '' }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed text-slate-400 italic">
                                {{ $section['intro'] }}
                            </p>
                        </div>
                    </div>

                    <button type="button" wire:click="closeModal"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-transparent text-slate-400 transition-all duration-200 hover:border-slate-700 hover:bg-slate-800 hover:text-white active:scale-95"
                        aria-label="Fermer">
                        <x-lucide-x class="h-5 w-5" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-5 py-5 sm:px-6">
                    <ul class="space-y-3.5">
                        @foreach ($section['points'] as $point)
                            <li class="flex gap-3 text-sm leading-relaxed text-slate-300">
                                <span
                                    class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-emerald-500/25 bg-emerald-500/10 text-emerald-400">
                                    <x-lucide-check class="h-3.5 w-3.5" />
                                </span>
                                <span>
                                    @if (is_array($point))
                                        <span class="font-semibold text-slate-100">{{ $point['label'] }}</span>
                                        <span class="text-slate-500"> — </span>
                                        {{ $point['text'] }}
                                    @else
                                        {{ $point }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Footer --}}
                <div
                    class="flex shrink-0 flex-wrap items-center justify-between gap-3 border-t border-slate-800 bg-slate-900/95 px-5 py-4 sm:px-6">
                    <button type="button" wire:click="closeModal"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700 px-4 py-2 text-sm font-medium text-slate-400 transition-all duration-200 hover:bg-slate-800 hover:text-white active:scale-95">
                        Fermer
                    </button>
                    <a wire:navigate href="{{ route($section['route']) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 hover:bg-indigo-500 active:scale-95">
                        {{ $section['cta'] }}
                        <x-lucide-arrow-right class="h-4 w-4" />
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>

