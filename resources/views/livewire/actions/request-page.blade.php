<div class="min-h-screen bg-[#070b14] text-slate-100 flex items-center justify-center p-4 md:p-8">

    @if ($done)
        {{-- ===================== SUCCESS STATE ===================== --}}
        <section class="w-full max-w-2xl">
            <div
                class="relative overflow-hidden rounded-3xl border border-emerald-500/20 bg-[#0f1523] p-8 md:p-12 shadow-2xl shadow-black/40 text-center">
                {{-- Glow --}}
                <div
                    class="absolute -top-20 left-1/2 -translate-x-1/2 h-40 w-64 rounded-full bg-emerald-500/20 blur-3xl">
                </div>

                <div class="relative">
                    <div
                        class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-500/30 bg-emerald-500/15">
                        <x-lucide-check-check class="h-10 w-10 text-emerald-400" />
                    </div>

                    <h2 class="text-2xl md:text-3xl font-bold text-white">
                        Demande soumise avec succès
                    </h2>
                    <p class="mt-3 text-slate-400 leading-relaxed max-w-md mx-auto">
                        Vous recevrez un e-mail contenant les informations de votre espace.
                        <span class="text-amber-300/90">Ne partagez pas ces détails.</span>
                    </p>

                    <button wire:click="resetForm" type="button"
                        class="mt-8 inline-flex h-12 items-center gap-2 rounded-xl bg-emerald-600 px-8 text-sm font-semibold text-white shadow-lg shadow-emerald-900/40 transition-all hover:bg-emerald-500 disabled:opacity-60">
                        <span wire:loading.remove wire:target="resetForm" class="inline-flex items-center gap-2">
                            <x-lucide-check class="h-4 w-4" />
                            Terminé
                        </span>
                        <span wire:loading wire:target="resetForm" class="inline-flex items-center gap-2">
                            <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                            En cours…
                        </span>
                    </button>
                </div>
            </div>
        </section>
    @else
        {{-- ===================== FORMULAIRE ===================== --}}
        <section class="w-full max-w-4xl">

            {{-- Header --}}
            <div class="mb-10 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-indigo-500/25 bg-indigo-500/10">
                    <x-lucide-rocket class="h-7 w-7 text-indigo-400" />
                </div>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white">
                    Créez votre espace
                </h1>
                <p class="mt-2 text-slate-400">
                    Prenez le contrôle. Soyez pro.
                </p>
            </div>

            {{-- Erreurs globales --}}
            @if ($errors->any())
                <div
                    class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-500/25 bg-rose-500/10 px-4 py-3.5 text-sm text-rose-200">
                    <x-lucide-octagon-alert class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" />
                    <div>
                        <p class="font-semibold">Formulaire incorrect</p>
                        <p class="mt-0.5 text-rose-300/80">Vérifiez que tous les champs obligatoires sont correctement
                            renseignés.</p>
                    </div>
                </div>
            @endif

            <p class="mb-6 text-center text-sm text-slate-500">
                Les champs marqués <span class="text-rose-400 font-medium">*</span> sont obligatoires
            </p>

            <form wire:submit.prevent="submit" class="space-y-6">

                {{-- ═══════ 1. INFORMATIONS PERSONNELLES ═══════ --}}
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
                    <div class="mb-5 flex items-center gap-3 border-b border-white/[0.05] pb-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/15 border border-indigo-500/20">
                            <x-lucide-user class="h-4.5 w-4.5 text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Informations personnelles</h3>
                            <p class="text-xs text-slate-500">Vos coordonnées en tant que responsable</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        {{-- Nom --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Nom <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="Votre nom">
                            @error('name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Prénoms --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Prénoms <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="prenames" type="text" id="prenames"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="Vos prénoms">
                            @error('prenames')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Email <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="email" type="email" id="email"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="vous@exemple.com">
                            @error('email')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Contact --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Contact (unique) <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="contacts" type="text" id="contacts"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="01 61 77 77 77">
                            @error('contacts')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Date de naissance --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Date de naissance
                            </label>
                            <input wire:model.live="birth_date" type="date" id="birth_date"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                            @error('birth_date')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Genre --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Genre <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="gender" id="gender"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le genre</option>
                                @foreach ($genders as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Fonction --}}
                        <div class="md:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Fonction
                            </label>
                            <input wire:model.live="job_name" type="text" id="job_name"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="Directeur, Entrepreneur…">
                            @error('job_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Domaine --}}
                        <div class="md:col-span-2">
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Nom de domaine <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="domain_name" type="text" id="domain_name"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="ecole-nom-ecole">
                            <p class="mt-1.5 text-[11px] text-amber-400/80">
                                Non modifiable après validation · sans espaces ni majuscules
                            </p>
                            @error('domain_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ═══════ 2. INFORMATIONS ÉCOLE ═══════ --}}
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
                    <div class="mb-5 flex items-center gap-3 border-b border-white/[0.05] pb-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500/15 border border-amber-500/20">
                            <x-lucide-school class="h-4.5 w-4.5 text-amber-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Informations de l’école</h3>
                            <p class="text-xs text-slate-500">Identité et organisation de l’établissement</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Nom complet de l’école <span class="text-rose-400">*</span>
                            </label>
                            <input wire:model.live="school_name" type="text" id="school_name"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="Nom complet de l’école">
                            @error('school_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Abréviation
                            </label>
                            <input wire:model.live="simple_name" type="text" id="simple_name"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="CEPGA">
                            @error('simple_name')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Niveau d’enseignement <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="level" id="level"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le niveau</option>
                                @foreach ($this->levels as $lev)
                                    <option value="{{ $lev }}">{{ $lev }}</option>
                                @endforeach
                            </select>
                            @error('level')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Devise de l’école
                            </label>
                            <input wire:model.live="school_devise" type="text" id="school_devise"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all"
                                placeholder="Succès · Discipline · Travail">
                            @error('school_devise')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Type d’enseignement <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="enseignement_type" id="enseignement_type"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le type</option>
                                @foreach ($enseignement_types as $et)
                                    <option value="{{ $et }}">{{ $et }}</option>
                                @endforeach
                            </select>
                            @error('enseignement_type')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Type d’école <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="school_type" id="school_type"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le type</option>
                                @foreach ($school_types as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('school_type')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Type de période <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="periode_type" id="periode_type"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le type de période</option>
                                @foreach ($periode_types as $pt)
                                    <option value="{{ $pt }}">{{ $pt }}</option>
                                @endforeach
                            </select>
                            @error('periode_type')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Les devoirs par <span class="text-rose-400">*</span>
                                {{ $periode_type ? $periode_type : 'période' }}
                            </label>
                            <select wire:model.live="devoirs_type" id="devoirs_type"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le type</option>
                                @foreach ($devoirs_types as $dt)
                                    <option value="{{ $dt }}">{{ $dt }}</option>
                                @endforeach
                            </select>
                            @error('devoirs_type')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ═══════ 3. LOCALISATION ═══════ --}}
                <div class="rounded-2xl border border-white/[0.06] bg-[#0f1523] p-5 sm:p-6 shadow-lg shadow-black/10">
                    <div class="mb-5 flex items-center gap-3 border-b border-white/[0.05] pb-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-500/15 border border-sky-500/20">
                            <x-lucide-map-pin class="h-4.5 w-4.5 text-sky-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Localisation de l’école</h3>
                            <p class="text-xs text-slate-500">Pays, département et ville</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Pays <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="country" id="country"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le pays</option>
                                @foreach ($countries as $ck => $ctn)
                                    <option value="{{ $ctn }}">{{ $ctn }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                Département <span class="text-rose-400">*</span>
                            </label>
                            <select wire:model.live="department" id="department"
                                class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                <option value="">Sélectionnez le département</option>
                                @foreach ($departments as $dk => $dn)
                                    <option value="{{ $dn }}">{{ $dn }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                    <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Ville (conditionnelle) --}}
                        <div class="md:col-span-2" wire:loading.class="opacity-50" wire:target="department">
                            @if ($department)
                                <label
                                    class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Ville <span class="text-rose-400">*</span>
                                </label>
                                <select wire:model.live="city" id="city"
                                    class="w-full rounded-xl border border-white/10 bg-[#070b14] px-4 py-3 text-sm text-slate-200 focus:border-indigo-500/50 focus:outline-none focus:ring-1 focus:ring-indigo-500/30 transition-all">
                                    <option value="">Sélectionnez la ville</option>
                                    @foreach ($cities as $ck => $cn)
                                        <option value="{{ $cn }}">{{ $cn }}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <p class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-400">
                                        <x-lucide-octagon-alert class="h-3.5 w-3.5" /> {{ $message }}
                                    </p>
                                @enderror
                            @else
                                <div
                                    class="flex h-[46px] items-center justify-center rounded-xl border border-dashed border-white/10 bg-[#070b14]/50 text-sm text-slate-500">
                                    Sélectionnez d’abord un département
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bouton submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="group relative flex h-13 w-full items-center justify-center gap-2 overflow-hidden rounded-2xl bg-indigo-600 text-sm font-semibold text-white shadow-xl shadow-indigo-900/40 transition-all hover:bg-indigo-500 disabled:opacity-60">
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                    <span wire:loading.remove wire:target="submit" class="relative inline-flex items-center gap-2">
                        Soumettre ma demande
                        <x-lucide-send class="h-4.5 w-4.5" />
                    </span>
                    <span wire:loading wire:target="submit" class="relative inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="h-4.5 w-4.5 animate-spin" />
                        Envoi en cours…
                    </span>
                </button>
            </form>

            {{-- Contact footer --}}
            <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="rounded-2xl border border-white/[0.05] bg-[#0f1523]/60 p-5 text-center transition-all hover:border-indigo-500/20">
                    <div
                        class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400">
                        <x-lucide-phone class="h-5 w-5" />
                    </div>
                    <h4 class="text-sm font-semibold text-white">Nous contacter</h4>
                    <p class="mt-1 text-xs text-slate-500">{{ env('APP_NUMBER') }}</p>
                </div>
                <div
                    class="rounded-2xl border border-white/[0.05] bg-[#0f1523]/60 p-5 text-center transition-all hover:border-sky-500/20">
                    <div
                        class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-400">
                        <x-lucide-mail class="h-5 w-5" />
                    </div>
                    <h4 class="text-sm font-semibold text-white">Support</h4>
                    <p class="mt-1 text-xs text-slate-500">support@educconnect.com</p>
                </div>
                <div
                    class="rounded-2xl border border-white/[0.05] bg-[#0f1523]/60 p-5 text-center transition-all hover:border-amber-500/20">
                    <div
                        class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                        <x-lucide-map-pin class="h-5 w-5" />
                    </div>
                    <h4 class="text-sm font-semibold text-white">Siège</h4>
                    <p class="mt-1 text-xs text-slate-500">Cotonou, Bénin</p>
                </div>
            </div>
        </section>
    @endif
</div>
