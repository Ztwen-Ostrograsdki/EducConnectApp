<div class="min-h-screen bg-[#070b14] text-slate-100 selection:bg-indigo-500/30" x-data="{
    open: $wire.entangle('openSections'),
    toggle(key) {
        this.open = { ...this.open, [key]: !this.open[key] };
    },
    openAll() {
        const next = {};
        Object.keys(this.open).forEach(k => next[k] = true);
        this.open = next;
    },
    closeAll() {
        const next = {};
        Object.keys(this.open).forEach(k => next[k] = false);
        this.open = next;
    }
}">

    <style>
        @keyframes profile-fade-up {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-enter {
            animation: profile-fade-up 0.28s ease-out both;
        }

        .section-card {
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .section-card:hover {
            border-color: rgba(255, 255, 255, 0.12);
        }

        .section-card.is-open {
            border-color: rgba(99, 102, 241, 0.22);
            box-shadow: 0 8px 30px -12px rgba(0, 0, 0, 0.45);
        }

        .tap-effect {
            transition: transform 0.12s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .tap-effect:active {
            transform: scale(0.98);
        }

        .chevron-spin {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .field-focus {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .section-body {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section-body.is-open {
            grid-template-rows: 1fr;
        }

        .section-body>.section-inner {
            overflow: hidden;
            min-height: 0;
        }

        .section-inner-content {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.22s ease, transform 0.26s ease;
            pointer-events: none;
        }

        .section-body.is-open .section-inner-content {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
            transition-delay: 0.04s;
        }

        .icon-pop {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .icon-pop.is-on {
            transform: scale(1.06);
        }
    </style>

    <div class="mx-auto max-w-4xl px-4 sm:px-6 py-8 sm:py-10">

        <header class="mb-8 profile-enter">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">

                <div class="space-y-2.5">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[11px] font-semibold uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                        Profil utilisateur
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Mettre à jour le profil
                    </h1>
                    <p class="text-sm text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                        <span class="text-slate-300 font-medium">{{ $user->getFullName(true) }}</span>
                        @if ($user->email)
                            <span class="text-slate-700">·</span>
                            <span class="font-mono text-slate-400 text-xs">{{ $user->email }}</span>
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span role="button" tabindex="0" @click="openAll()" @keydown.enter.prevent="openAll()"
                        @keydown.space.prevent="openAll()"
                        class="tap-effect cursor-pointer h-9 px-3.5 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:text-white inline-flex items-center gap-1.5">
                        <x-lucide-chevrons-down class="w-3.5 h-3.5" />
                        Tout ouvrir
                    </span>
                    <span role="button" tabindex="0" @click="closeAll()" @keydown.enter.prevent="closeAll()"
                        @keydown.space.prevent="closeAll()"
                        class="tap-effect cursor-pointer h-9 px-3.5 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:text-white inline-flex items-center gap-1.5">
                        <x-lucide-chevrons-up class="w-3.5 h-3.5" />
                        Tout fermer
                    </span>
                </div>
            </div>
        </header>

        <form wire:submit="save" class="space-y-3.5">
            {{-- État civil --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.04s" :class="open.civil && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('civil')" @keydown.enter.prevent="toggle('civil')"
                    @keydown.space.prevent="toggle('civil')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-500/15 border border-sky-500/25"
                            :class="open.civil && 'is-on shadow-[0_0_16px_-4px_rgba(56,189,248,0.45)]'">
                            <x-lucide-user class="w-5 h-5 text-sky-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">État civil</h2>
                            <p class="text-[11px] text-slate-500 truncate">Identité, naissance, situation familiale</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.civil && 'rotate-180 bg-sky-500/15 border-sky-500/25 text-sky-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.civil && 'is-open'">
                    <div class="section-inner">
                        <div
                            class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04] grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Nom <span
                                        class="text-rose-400">*</span></label>
                                <input type="text" wire:model="name"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 @error('name') border-rose-500/50 @enderror" />
                                @error('name')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Prénoms
                                    <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model="prenames"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 @error('prenames') border-rose-500/50 @enderror" />
                                @error('prenames')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Genre</label>
                                <select wire:model="gender"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30">
                                    <option value="">— Sélectionner —</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                                @error('gender')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Situation
                                    matrimoniale</label>
                                <select wire:model="marital_status"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30">
                                    <option value="">— Sélectionner —</option>
                                    <option value="Célibataire">Célibataire</option>
                                    <option value="Marié(e)">Marié(e)</option>
                                    <option value="Divorcé(e)">Divorcé(e)</option>
                                    <option value="Veuf(ve)">Veuf(ve)</option>
                                    <option value="Concubinage">Concubinage</option>
                                </select>
                                @error('marital_status')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Date de
                                    naissance</label>
                                <input type="date" wire:model="birth_date"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 @error('birth_date') border-rose-500/50 @enderror" />
                                @error('birth_date')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Lieu de
                                    naissance</label>
                                <input type="text" wire:model="birth_place" placeholder="Ex. Cotonou"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30" />
                                @error('birth_place')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Profession</label>
                                <input type="text" wire:model="job_name" placeholder="Ex. Enseignant"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30" />
                                @error('job_name')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Coordonnées --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.08s" :class="open.contact && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('contact')"
                    @keydown.enter.prevent="toggle('contact')" @keydown.space.prevent="toggle('contact')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 border border-emerald-500/25"
                            :class="open.contact && 'is-on shadow-[0_0_16px_-4px_rgba(52,211,153,0.45)]'">
                            <x-lucide-map-pin class="w-5 h-5 text-emerald-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">Coordonnées</h2>
                            <p class="text-[11px] text-slate-500 truncate">Contacts, adresse, localisation</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.contact && 'rotate-180 bg-emerald-500/15 border-emerald-500/25 text-emerald-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.contact && 'is-open'">
                    <div class="section-inner">
                        <div
                            class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04] grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Contacts</label>
                                <input type="text" wire:model="contacts" placeholder="Téléphone(s)"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30" />
                                @error('contacts')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Adresse</label>
                                <input type="text" wire:model="adresse" placeholder="Adresse complète"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30" />
                                @error('adresse')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Pays</label>
                                <input type="text" wire:model="country"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30" />
                                @error('country')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Ville</label>
                                <input type="text" wire:model="city"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30" />
                                @error('city')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Département
                                    / Région</label>
                                <input type="text" wire:model="department"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30" />
                                @error('department')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Pièce d'identité --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.12s" :class="open.identity && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('identity')"
                    @keydown.enter.prevent="toggle('identity')" @keydown.space.prevent="toggle('identity')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 border border-amber-500/25"
                            :class="open.identity && 'is-on shadow-[0_0_16px_-4px_rgba(251,191,36,0.4)]'">
                            <x-lucide-id-card class="w-5 h-5 text-amber-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">Pièce d’identité & matricule</h2>
                            <p class="text-[11px] text-slate-500 truncate">Carte d’identité, passeport, matricule</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.identity && 'rotate-180 bg-amber-500/15 border-amber-500/25 text-amber-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.identity && 'is-open'">
                    <div class="section-inner">
                        <div
                            class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04] grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">N°
                                    matricule</label>
                                <input type="text" wire:model="matricule_number"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 font-mono focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30" />
                                @error('matricule_number')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Type de
                                    pièce</label>
                                <select wire:model="ID_card_type"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30">
                                    <option value="">— Sélectionner —</option>
                                    <option value="CNI">CNI</option>
                                    <option value="Passeport">Passeport</option>
                                    <option value="Permis de conduire">Permis de conduire</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                @error('ID_card_type')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">N° de la
                                    pièce</label>
                                <input type="text" wire:model="ID_card_numder"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 font-mono focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30" />
                                @error('ID_card_numder')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Lieu de
                                    délivrance</label>
                                <input type="text" wire:model="ID_card_delivery_place"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30" />
                                @error('ID_card_delivery_place')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Date
                                    d’expiration</label>
                                <input type="date" wire:model="ID_card_expired_date"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/30" />
                                @error('ID_card_expired_date')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Carrière --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.16s" :class="open.career && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('career')"
                    @keydown.enter.prevent="toggle('career')" @keydown.space.prevent="toggle('career')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-500/15 border border-violet-500/25"
                            :class="open.career && 'is-on shadow-[0_0_16px_-4px_rgba(167,139,250,0.45)]'">
                            <x-lucide-briefcase class="w-5 h-5 text-violet-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">Carrière & diplômes</h2>
                            <p class="text-[11px] text-slate-500 truncate">Corps, statut, recrutement, formations</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.career && 'rotate-180 bg-violet-500/15 border-violet-500/25 text-violet-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.career && 'is-open'">
                    <div class="section-inner">
                        <div
                            class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04] grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Date de
                                    prise de service</label>
                                <input type="date" wire:model="first_take_job_date"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('first_take_job_date')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Corps</label>
                                <input type="text" wire:model="body" placeholder="Ex. Enseignement secondaire"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('body')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Statut
                                    officiel</label>
                                <input type="text" wire:model="official_status"
                                    placeholder="Ex. Titulaire, Contractuel"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('official_status')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Réf. de
                                    recrutement</label>
                                <input type="text" wire:model="references_of_recrutement"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 font-mono focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('references_of_recrutement')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Diplôme
                                    académique</label>
                                <input type="text" wire:model="academic_graduate"
                                    placeholder="Ex. Licence, Master"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('academic_graduate')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Diplôme
                                    professionnel</label>
                                <input type="text" wire:model="professionnal_graduate"
                                    placeholder="Ex. CAPES, CAP"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-1 focus:ring-violet-500/30" />
                                @error('professionnal_graduate')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Grades --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.2s" :class="open.grades && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('grades')"
                    @keydown.enter.prevent="toggle('grades')" @keydown.space.prevent="toggle('grades')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/15 border border-orange-500/25"
                            :class="open.grades && 'is-on shadow-[0_0_16px_-4px_rgba(251,146,60,0.4)]'">
                            <x-lucide-award class="w-5 h-5 text-orange-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">Grades & indices</h2>
                            <p class="text-[11px] text-slate-500 truncate">Grade / indice actuel et payé</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.grades && 'rotate-180 bg-orange-500/15 border-orange-500/25 text-orange-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.grades && 'is-open'">
                    <div class="section-inner">
                        <div
                            class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04] grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Grade
                                    actuel</label>
                                <input type="text" wire:model="current_grade"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30" />
                                @error('current_grade')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Indice
                                    actuel</label>
                                <input type="text" wire:model="current_index"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 font-mono focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30" />
                                @error('current_index')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Grade
                                    payé</label>
                                <input type="text" wire:model="payed_grade"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30" />
                                @error('payed_grade')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Indice
                                    payé</label>
                                <input type="text" wire:model="payed_index"
                                    class="field-focus w-full h-11 px-3.5 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 font-mono focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30" />
                                @error('payed_index')
                                    <p class="text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Observations --}}
            <section
                class="section-card rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-lg shadow-black/15 profile-enter"
                style="animation-delay: 0.24s" :class="open.notes && 'is-open'">
                <span role="button" tabindex="0" @click="toggle('notes')"
                    @keydown.enter.prevent="toggle('notes')" @keydown.space.prevent="toggle('notes')"
                    class="tap-effect cursor-pointer w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-white/[0.03]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="icon-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-500/15 border border-slate-500/25"
                            :class="open.notes && 'is-on shadow-[0_0_16px_-4px_rgba(148,163,184,0.35)]'">
                            <x-lucide-sticky-note class="w-5 h-5 text-slate-400" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-semibold text-white">Observations</h2>
                            <p class="text-[11px] text-slate-500 truncate">Notes libres sur le dossier</p>
                        </div>
                    </div>
                    <span
                        class="chevron-spin flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 border border-white/10 text-slate-400"
                        :class="open.notes && 'rotate-180 bg-slate-500/20 border-slate-500/30 text-slate-300'">
                        <x-lucide-chevron-down class="w-4 h-4" />
                    </span>
                </span>

                <div class="section-body" :class="open.notes && 'is-open'">
                    <div class="section-inner">
                        <div class="section-inner-content px-5 pb-5 pt-1 border-t border-white/[0.04]">
                            <textarea wire:model="observations" rows="4" placeholder="Observations éventuelles…"
                                class="field-focus w-full px-3.5 py-3 rounded-xl bg-[#070b14] border border-white/10 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-slate-500/50 focus:ring-1 focus:ring-slate-500/30 resize-y min-h-[100px]"></textarea>
                            @error('observations')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="sticky bottom-4 z-10 mt-6 rounded-2xl bg-[#0f1523]/95 backdrop-blur-md border border-white/10 p-4 shadow-2xl shadow-black/40 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 profile-enter"
                style="animation-delay: 0.28s">
                <p class="text-[11px] text-slate-500 hidden sm:block">
                    Les champs marqués <span class="text-rose-400">*</span> sont obligatoires.
                </p>
                <div class="flex items-center gap-2 justify-end">
                    <span role="button" tabindex="0" wire:click="resetForm"
                        wire:loading.class="opacity-50 pointer-events-none" wire:target="resetForm,save"
                        class="tap-effect cursor-pointer h-11 px-4 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 hover:border-white/20 inline-flex items-center justify-center gap-1.5">
                        <span wire:loading.remove wire:target="resetForm" class="inline-flex items-center gap-1.5">
                            <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                            Annuler
                        </span>
                        <span wire:loading wire:target="resetForm" class="inline-flex items-center gap-1.5">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                        </span>
                    </span>

                    <button type="submit" wire:loading.attr="disabled" wire:target="save,resetForm"
                        class="tap-effect h-11 px-5 rounded-xl text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-500/30 hover:bg-indigo-500/30 hover:border-indigo-400/40 inline-flex items-center justify-center gap-1.5 disabled:opacity-50 min-w-[150px]">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-1.5">
                            <x-lucide-save class="w-3.5 h-3.5" />
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                            Enregistrement…
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

