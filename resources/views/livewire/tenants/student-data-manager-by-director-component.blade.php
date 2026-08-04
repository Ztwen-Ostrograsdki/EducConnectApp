<div class="w-full max-w-full overflow-x-hidden p-3 sm:p-4 lg:p-6">

    {{-- ===================== HEADER ===================== --}}
    <section class="space-y-6 max-w-5xl mx-auto mb-10">
        <div
            class="border rounded-[1.75rem] border-white/5 bg-slate-900/70 backdrop-blur-xl shadow-xl shadow-black/20 overflow-hidden">

            {{-- Bandeau titre --}}
            <div
                class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-white">
                        Mise à jour des informations
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Modifiez les données de l’apprenant
                    </p>
                </div>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[11px] font-medium w-fit">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                    Édition
                </span>
            </div>

            {{-- Identité --}}
            <div class="p-5 sm:p-6">
                <div class="flex items-center gap-4 sm:gap-5">

                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        <img src="{{ $student->profil_photo_url }}" alt="{{ $student->prenames }} {{ $student->name }}"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover ring-2 ring-white/10">
                        <span
                            class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-[#0f1523]"></span>
                    </div>

                    {{-- Nom + meta --}}
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-xl font-bold text-white truncate">
                            {{ $student->prenames }}
                            <span class="text-slate-400 font-semibold">{{ $student->name }}</span>
                        </h1>

                        <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                            <span>
                                Matricule
                                <span class="font-mono text-slate-300 ml-1">{{ $student->matricule }}</span>
                            </span>
                            <span class="text-slate-700">·</span>
                            <span>
                                EducMaster
                                <span class="text-slate-300 ml-1">{{ $student->educMaster }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ===================== FORMULAIRE ===================== --}}
    <div class="space-y-6 max-w-5xl mx-auto">

        {{-- 1. INFORMATIONS PERSONNELLES --}}
        <div
            class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 sm:p-8 shadow-xl shadow-black/20">
            <div class="flex items-center gap-3 mb-7 pb-4 border-b border-white/5">
                <div
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/20">
                    <x-lucide-user class="w-5 h-5 text-indigo-400" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Informations personnelles</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Identité et coordonnées de l’apprenant</p>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Nom + Prénoms --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="name">
                            Nom <span class="text-red-400">*</span>
                        </label>
                        <input wire:model.live="name" type="text" id="name"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="Nom de l'apprenant">
                        @error('name')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="prenames">
                            Prénoms <span class="text-red-400">*</span>
                        </label>
                        <input wire:model.live="prenames" type="text" id="prenames"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="Prénoms de l'apprenant">
                        @error('prenames')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- EducMaster + Contact + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="educMaster">
                            EducMaster <span class="text-red-400">*</span>
                        </label>
                        <input wire:model.live="educMaster" type="text" id="educMaster"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="N° EducMaster">
                        @error('educMaster')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="contacts">
                            Contact parent légitime <span class="text-red-400">*</span>
                        </label>
                        <input wire:model.live="contacts" type="text" id="contacts"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="01 61 77 77 77">
                        @error('contacts')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="email">
                            Email <span class="text-sky-400 text-xs font-normal">(facultatif)</span>
                        </label>
                        <input wire:model.live="email" type="email" id="email"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="email@exemple.com">
                        @error('email')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Naissance + Genre --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="birth_date">
                            Date de naissance <span class="text-red-400">*</span>
                        </label>
                        <input wire:model.live="birth_date" type="date" id="birth_date"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all">
                        @error('birth_date')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="birth_place">
                            Lieu de naissance
                        </label>
                        <input wire:model.live="birth_place" type="text" id="birth_place"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all"
                            placeholder="Ville de naissance">
                        @error('birth_place')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="gender">
                            Genre <span class="text-red-400">*</span>
                        </label>
                        <select wire:model.live="gender" id="gender"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500/50 transition-all">
                            <option value="">Sélectionnez le genre</option>
                            @foreach ($genders as $kg => $g)
                                <option class="bg-slate-900" value="{{ $kg }}">{{ $g }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. ADRESSE --}}
        <div
            class="rounded-[1.75rem] border border-sky-500/20 bg-slate-900/70 backdrop-blur-xl p-6 sm:p-8 shadow-xl shadow-sky-950/20">
            <div class="flex items-center gap-3 mb-7 pb-4 border-b border-white/5">
                <div
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-sky-500/15 border border-sky-500/20">
                    <x-lucide-map-pin-check class="w-5 h-5 text-sky-400" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Adresse actuelle</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Localisation de l’apprenant</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="country">
                            Pays <span class="text-red-400">*</span>
                        </label>
                        <select wire:model.live="country" id="country"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500/50 transition-all">
                            <option value="">Sélectionnez le pays</option>
                            @foreach ($countries as $ck => $ctn)
                                <option class="bg-slate-900" value="{{ $ctn }}">{{ $ctn }}
                                </option>
                            @endforeach
                        </select>
                        @error('country')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2" for="department">
                            Département <span class="text-red-400">*</span>
                        </label>
                        <select wire:model.live="department" id="department"
                            class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500/50 transition-all">
                            <option value="">Sélectionnez le département</option>
                            @foreach ($departments as $dk => $dn)
                                <option class="bg-slate-900" value="{{ $dn }}">{{ $dn }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Loading --}}
                    <div wire:loading wire:target="department,city"
                        class="flex items-center gap-3 h-12 mt-7 text-slate-500">
                        <x-lucide-loader class="w-5 h-5 animate-spin" />
                        <span class="text-sm">Chargement des villes…</span>
                    </div>

                    @if ($department)
                        <div wire:loading.remove wire:target="department,city">
                            <label class="block text-sm font-medium text-slate-300 mb-2" for="city">
                                Ville <span class="text-red-400">*</span>
                            </label>
                            <select wire:model.live="city" id="city"
                                class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500/50 transition-all">
                                <option value="">Sélectionnez la ville</option>
                                @foreach ($cities as $ck => $cn)
                                    <option class="bg-slate-900" value="{{ $cn }}">{{ $cn }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city')
                                <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                                    <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. GÉNITEURS --}}
        <div
            class="rounded-[1.75rem] border border-white/5 bg-slate-900/70 backdrop-blur-xl p-6 sm:p-8 shadow-xl shadow-black/20">
            <div class="flex items-center gap-3 mb-7 pb-4 border-b border-white/5">
                <div
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/20">
                    <x-lucide-users class="w-5 h-5 text-violet-400" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Informations sur les géniteurs</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Si vivants (facultatif)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2" for="father_full_name">
                        Nom et prénoms du père
                        <span class="text-sky-400 text-xs font-normal">(facultatif)</span>
                    </label>
                    <input wire:model.live="father_full_name" type="text" id="father_full_name"
                        class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/50 transition-all"
                        placeholder="Nom complet du père">
                    @error('father_full_name')
                        <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                            <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2" for="mother_full_name">
                        Nom et prénoms de la mère
                        <span class="text-sky-400 text-xs font-normal">(facultatif)</span>
                    </label>
                    <input wire:model.live="mother_full_name" type="text" id="mother_full_name"
                        class="w-full h-12 px-4 rounded-xl bg-slate-950/70 border border-white/10 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/50 transition-all"
                        placeholder="Nom complet de la mère">
                    @error('mother_full_name')
                        <span class="flex items-center gap-2 mt-2 text-sm text-red-400">
                            <x-lucide-octagon-alert class="w-4 h-4 shrink-0" />
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- BOUTON FINAL --}}
        <div class="pt-2 pb-10">
            <button type="button" wire:click="finish" wire:loading.attr="disabled"
                class="group relative w-full h-14 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-700/35 hover:from-emerald-700/35 hover:to-emerald-700 text-white font-semibold text-base shadow-lg shadow-emerald-900/30 transition-all active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed overflow-hidden hover:text-black">
                <span class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span class="relative flex items-center justify-center gap-2.5" wire:loading.remove
                    wire:target="finish">
                    <span>Enregistrer les modifications</span>
                    <x-lucide-save class="w-5 h-5" />
                </span>
                <span class="relative flex items-center justify-center gap-2.5" wire:loading wire:target="finish">
                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                    <span>Enregistrement en cours…</span>
                </span>
            </button>
        </div>
    </div>
</div>

