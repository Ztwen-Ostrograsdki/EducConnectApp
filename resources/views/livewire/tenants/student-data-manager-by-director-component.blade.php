<div class="w-full max-w-full overflow-x-hidden p-3 sm:p-4 lg:p-6">

    {{-- ===================== HEADER ===================== --}}
    <section class="mb-8">
        <div
            class="relative rounded-[2rem] border border-white/5 bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-2xl shadow-indigo-950/40">

            {{-- COVER --}}
            <div class="relative h-40 sm:h-52 w-full overflow-hidden">
                <img src="{{ $student->profil_photo_url }}" alt="Photo de couverture"
                    class="w-full h-full object-cover object-top scale-105" />

                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/85 via-slate-900/50 to-violet-950/75">
                </div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-sky-500/15 via-transparent to-transparent">
                </div>
                <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-indigo-600/25 blur-3xl"></div>
                <div class="absolute top-10 right-1/4 w-64 h-64 rounded-full bg-violet-500/20 blur-3xl"></div>
                <div class="absolute -bottom-16 right-0 w-72 h-72 rounded-full bg-sky-500/15 blur-3xl"></div>
                <div
                    class="absolute bottom-0 inset-x-0 h-28 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent">
                </div>

                {{-- Titre centré --}}
                <div class="absolute inset-0 flex items-center justify-center z-10 px-4">
                    <div class="px-6 py-3.5 rounded-2xl bg-black/30 backdrop-blur-md border border-white/10">
                        <h2
                            class="text-xl sm:text-2xl lg:text-3xl font-semibold text-white/90 tracking-wide text-center">
                            Mise à jour des informations
                        </h2>
                        <p class="mt-1 text-sm text-slate-400 text-center hidden sm:block">
                            Apprenant • {{ $student->prenames }} {{ $student->name }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- BODY HEADER --}}
            <div class="relative px-5 sm:px-8 pb-8 -mt-16">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6">

                    {{-- Avatar --}}
                    <div class="relative group shrink-0">
                        <div
                            class="absolute -inset-1.5 rounded-[1.75rem] bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-400 opacity-60 blur-md group-hover:opacity-90 transition-opacity duration-500">
                        </div>
                        <div
                            class="absolute -inset-0.5 rounded-[1.6rem] bg-gradient-to-br from-indigo-400 via-violet-400 to-sky-300 opacity-80">
                        </div>

                        <div
                            class="relative w-32 h-32 rounded-[1.5rem] bg-slate-900 ring-4 ring-slate-900 overflow-hidden shadow-2xl">
                            <img src="{{ $student->profil_photo_url }}" alt="Photo de profil"
                                class="w-full h-full object-cover" />
                        </div>

                        <span class="absolute bottom-2 right-2 flex h-5 w-5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                            <span
                                class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 ring-4 ring-slate-900"></span>
                        </span>
                    </div>

                    {{-- Infos rapides --}}
                    <div class="flex-1 min-w-0 text-center sm:text-left pt-2 sm:pt-0">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                            {{ $student->prenames }}
                            <span class="text-slate-300">{{ $student->name }}</span>
                        </h1>

                        <div
                            class="mt-2.5 flex flex-wrap items-center justify-center sm:justify-start gap-x-4 gap-y-1.5 text-sm">
                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                <span class="text-slate-500">Matricule</span>
                                <span class="font-mono text-slate-200">{{ $student->matricule }}</span>
                            </span>
                            <span class="hidden sm:inline text-slate-700">•</span>
                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                <span class="text-slate-500">EducMaster</span>
                                <span class="font-medium text-slate-300">{{ $student->educMaster }}</span>
                            </span>
                        </div>

                        <div class="mt-3 flex justify-center sm:justify-start">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/20 text-indigo-300 text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                Terminale F2-1
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
                class="group relative w-full h-14 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-semibold text-base shadow-lg shadow-emerald-900/30 transition-all active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed overflow-hidden">
                <span class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span class="relative flex items-center justify-center gap-2.5" wire:loading.remove
                    wire:target="finish">
                    <span>Enregistrer les modifications</span>
                    <x-lucide-send class="w-5 h-5" />
                </span>
                <span class="relative flex items-center justify-center gap-2.5" wire:loading wire:target="finish">
                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                    <span>Enregistrement en cours…</span>
                </span>
            </button>
        </div>
    </div>
</div>
