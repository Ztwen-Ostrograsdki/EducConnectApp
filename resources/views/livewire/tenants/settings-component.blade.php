<div class="min-h-screen bg-[#070b14] text-slate-100">
    <div class="mx-auto max-w-full px-4 sm:px-6 py-8">

        {{-- Header --}}
        <header class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-semibold uppercase tracking-wider mb-2">
                        <x-lucide-settings class="w-3 h-3" />
                        Configuration
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Paramétrages</h1>
                    <p class="mt-1 text-sm text-slate-500">Gérez les options de votre établissement</p>
                </div>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- Sidebar tabs --}}
            <nav class="lg:w-56 shrink-0 space-y-1">
                @foreach ([['general', 'Général', 'building-2'], ['academic', 'Académique', 'graduation-cap'], ['notifications', 'Notifications', 'bell'], ['security', 'Sécurité', 'shield']] as [$key, $label, $icon])
                    <button wire:click="setTab('{{ $key }}')"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all
                                   {{ $activeTab === $key
                                       ? 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/25'
                                       : 'text-slate-400 hover:bg-white/5 hover:text-slate-200 border border-transparent' }}">
                        @switch($icon)
                            @case('building-2')
                                <x-lucide-building-2 class="w-4 h-4 shrink-0" />
                            @break

                            @case('graduation-cap')
                                <x-lucide-graduation-cap class="w-4 h-4 shrink-0" />
                            @break

                            @case('bell')
                                <x-lucide-bell class="w-4 h-4 shrink-0" />
                            @break

                            @case('shield')
                                <x-lucide-shield class="w-4 h-4 shrink-0" />
                            @break
                        @endswitch
                        {{ $label }}
                    </button>
                @endforeach
            </nav>

            {{-- Content --}}
            <div class="flex-1 min-w-0">

                {{-- ═══ GÉNÉRAL ═══ --}}
                @if ($activeTab === 'general')
                    <section
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                        <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/20 flex items-center justify-center">
                                <x-lucide-building-2 class="w-4 h-4 text-indigo-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Informations générales</h2>
                                <p class="text-[11px] text-slate-500">Identité de l’établissement</p>
                            </div>
                        </div>

                        <form wire:submit="saveGeneral" class="p-5 sm:p-6 space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Nom de l’école <span class="text-rose-400">*</span>
                                    </label>
                                    <input @disabled(true) type="text" wire:model="school_name"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/30 transition-all disabled:opacity-40
                                                  @error('school_name') border-rose-500/50 @enderror">
                                    @error('school_name')
                                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Devise</label>
                                    <input type="text" wire:model="school_devise"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 transition-all">
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Contacts</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-600">
                                            <x-lucide-phone class="w-4 h-4" />
                                        </span>
                                        <input @disabled(true) type="text" wire:model="contacts"
                                            class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 pl-10 pr-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 transition-all font-mono disabled:opacity-40">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Date de naissance
                                    </label>
                                    <input type="date" wire:model="birth_date"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-rose-500/50 transition-all font-mono">
                                    @error('birth_date')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Email</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-600">
                                            <x-lucide-mail class="w-4 h-4" />
                                        </span>
                                        <input disabled @disabled(1) type="email" wire:model="email"
                                            class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 pl-10 pr-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500/50 transition-all disabled:opacity-40 @error('email') border-rose-500/50 @enderror">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mb-4 sm:col-span-2">
                                    <div>
                                        <label class="block text-sm font-medium mb-2 text-gray-300" for="department">Le
                                            département
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select wire:model.live='department' id="department"
                                                class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                                <option value="">Sélectionnez le département
                                                </option>
                                                @foreach ($this->departments as $dk => $dn)
                                                    <option class="bg-slate-800 text-slate-300"
                                                        value="{{ $dn }}">
                                                        {{ $dn }}</option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div wire:loading wire:target='department' wire:target='city'>
                                        <div class="py-3 mt-3 flex justify-center items-center gap-x-3 text-gray-600">
                                            <x-lucide-loader class="w-5 h-5 animate-spin" />
                                            <h5>Chargement en cours ...</h5>
                                        </div>
                                    </div>
                                    @if ($department)
                                        <div data-animate='card' wire:target='department' wire:target='city'
                                            wire:loading.remove>
                                            <label class="block text-sm font-medium mb-2 text-gray-300"
                                                for="city">La ville
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <select wire:model.live='city' id="city"
                                                    class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                                    <option value="">Sélectionnez la ville</option>
                                                    @foreach ($cities as $ck => $cn)
                                                        <option class="bg-slate-800 text-slate-300"
                                                            value="{{ $cn }}">
                                                            {{ $cn }}</option>
                                                    @endforeach
                                                </select>
                                                @error('city')
                                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="h-11 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2">
                                    <span wire:loading.remove wire:target="saveGeneral"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-save class="w-4 h-4" />
                                        Enregistrer
                                    </span>
                                    <span wire:loading wire:target="saveGeneral" class="inline-flex items-center gap-2">
                                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        Enregistrement…
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                @endif

                {{-- ═══ ACADÉMIQUE ═══ --}}
                @if ($activeTab === 'academic')
                    <section
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                        <div
                            class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center">
                                    <x-lucide-graduation-cap class="w-4 h-4 text-emerald-400" />
                                </div>
                                <div>
                                    <h2 class="text-sm font-semibold text-white">Paramètres académiques</h2>
                                    <p class="text-[11px] text-slate-500">Périodes, seuils et bulletins</p>
                                </div>
                            </div>

                            @if ($activeSchoolYearSlug)
                                <span
                                    class="text-[11px] font-mono px-2.5 py-1 rounded-lg bg-white/[0.04] border border-white/[0.06] text-slate-400">
                                    {{ $activeSchoolYearSlug }}
                                </span>
                            @endif
                        </div>

                        <form wire:submit="saveAcademic" class="p-5 sm:p-6 space-y-5">

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Type de période
                                    </label>
                                    <select wire:model.live="periode_type"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 transition-all">
                                        <option value="semestre">Semestre</option>
                                        <option value="trimestre">Trimestre</option>
                                    </select>
                                    @error('periode_type')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Type de devoirs
                                    </label>
                                    <select wire:model="devoirs_type"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 transition-all">
                                        <option value="devoir1-devoir2">Devoir 1 / Devoir 2</option>
                                        <option value="devoir-compo">Devoir / Composition</option>
                                    </select>
                                    @error('devoirs_type')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Seuil de passage (/20)
                                    </label>
                                    <input type="number" step="0.25" wire:model="min_average_to_pass"
                                        min="10" max="20"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 transition-all">
                                    @error('min_average_to_pass')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Période active
                                    </label>
                                    <select wire:model="active_period"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 transition-all">
                                        <option value="">— Aucune — (Fermer toutes les périodes)</option>
                                        @foreach ($this->periodOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('active_period')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Verrouillage des notes --}}
                            <div>
                                <label
                                    class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Période verrouillée pour la saisie
                                </label>
                                <select wire:model="locked_for_period"
                                    class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-emerald-500/50 transition-all">
                                    <option value="">— Aucune —</option>
                                    @foreach ($this->periodOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-[11px] text-slate-500">Les enseignants ne pourront plus saisir de
                                    notes pour cette période.</p>
                                @error('locked_for_period')
                                    <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                    Périodes verrouillées (historique)
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($this->periodOptions as $value => $label)
                                        <label
                                            class="inline-flex items-center gap-2 rounded-lg bg-[#070b14] border border-white/[0.06] px-3 py-2 cursor-pointer has-[:checked]:border-emerald-500/50 has-[:checked]:bg-emerald-500/10 transition-all">
                                            <input type="checkbox" wire:model="marks_locked_for_periods"
                                                value="{{ $value }}"
                                                class="rounded border-white/20 bg-transparent text-emerald-500 focus:ring-emerald-500/40">
                                            <span class="text-xs text-slate-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('marks_locked_for_periods')
                                    <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                                @error('marks_locked_for_periods.*')
                                    <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Toggles --}}
                            <div x-data="{ visible: $wire.entangle('yearly_average_is_visible') }"
                                class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                @click="visible = !visible">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-lucide-book-open class="w-4 h-4 text-sky-400 shrink-0" />
                                    <div>
                                        <p class="text-sm font-medium text-slate-200">Moyenne annuelle visible</p>
                                        <p class="text-[11px] text-slate-500">Afficher la moyenne annuelle sur les
                                            bulletins et le dashboard</p>
                                    </div>
                                </div>

                                <span class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                    :class="visible ? 'bg-emerald-500' : 'bg-slate-700'">
                                    <span
                                        class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                        :class="visible ? 'translate-x-5' : 'translate-x-0'"></span>
                                </span>
                            </div>
                            {{-- Statuts --}}
                            <div class="grid sm:grid-cols-2 gap-3">
                                {{-- is_active --}}
                                <div
                                    class="rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span
                                            class="h-2 w-2 rounded-full shrink-0 {{ $is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-200">Année active</p>
                                            <p class="text-[11px] text-slate-500">
                                                {{ $is_active ? 'Année scolaire courante' : 'Non active' }}</p>
                                        </div>
                                    </div>

                                    @if ($is_active)
                                        <button type="button"
                                            wire:click="deactivateSchoolYear('{{ $activeSchoolYearSlug }}')"
                                            class="shrink-0 text-[11px] font-semibold px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-colors">
                                            Désactiver
                                        </button>
                                    @else
                                        <button type="button"
                                            wire:click="activateSchoolYear('{{ $activeSchoolYearSlug }}')"
                                            class="shrink-0 text-[11px] font-semibold px-3 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-colors">
                                            Activer
                                        </button>
                                    @endif
                                </div>

                                {{-- is_closed --}}
                                <div
                                    class="rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span
                                            class="h-2 w-2 rounded-full shrink-0 {{ $is_closed ? 'bg-rose-400' : 'bg-slate-600' }}"></span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-200">Clôture</p>
                                            <p class="text-[11px] text-slate-500">
                                                {{ $is_closed ? 'Année clôturée' : 'Année ouverte' }}</p>
                                        </div>
                                    </div>

                                    @if ($is_closed)
                                        <button type="button"
                                            wire:click="reopenSchoolYear('{{ $activeSchoolYearSlug }}')"
                                            class="shrink-0 text-[11px] font-semibold px-3 py-1.5 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 hover:bg-sky-500/20 transition-colors">
                                            Réouvrir
                                        </button>
                                    @else
                                        <button type="button"
                                            wire:click="closeSchoolYear('{{ $activeSchoolYearSlug }}')"
                                            class="shrink-0 text-[11px] font-semibold px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-colors">
                                            Clôturer
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="h-11 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2">
                                    <span wire:loading.remove wire:target="saveAcademic"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-save class="w-4 h-4" />
                                        Enregistrer
                                    </span>
                                    <span wire:loading wire:target="saveAcademic"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        Enregistrement…
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                @endif

                {{-- ═══ NOTIFICATIONS ═══ --}}
                @if ($activeTab === 'notifications')
                    <section
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                        <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/20 flex items-center justify-center">
                                <x-lucide-bell class="w-4 h-4 text-amber-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Notifications</h2>
                                <p class="text-[11px] text-slate-500">Canaux et alertes automatiques</p>
                            </div>
                        </div>

                        <form wire:submit="saveNotifications" class="p-5 sm:p-6 space-y-3">
                            @foreach ([['notify_parents_marks', 'Notes aux parents', 'Envoyer une alerte quand de nouvelles notes sont publiées', 'megaphone'], ['notify_teachers_absences', 'Absences aux enseignants', 'Notifier les profs des absences de leurs classes', 'user-x'], ['notify_director_payments', 'Paiements au directeur', 'Alerter en cas de retards de paiement', 'credit-card'], ['email_digest', 'Résumé hebdomadaire', 'Email récapitulatif chaque semaine', 'mail']] as [$model, $title, $desc, $icon])
                                <label
                                    class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @switch($icon)
                                            @case('megaphone')
                                                <x-lucide-megaphone class="w-4 h-4 text-indigo-400 shrink-0" />
                                            @break

                                            @case('user-x')
                                                <x-lucide-user-x class="w-4 h-4 text-rose-400 shrink-0" />
                                            @break

                                            @case('credit-card')
                                                <x-lucide-credit-card class="w-4 h-4 text-emerald-400 shrink-0" />
                                            @break

                                            @case('mail')
                                                <x-lucide-mail class="w-4 h-4 text-sky-400 shrink-0" />
                                            @break
                                        @endswitch
                                        <div>
                                            <p class="text-sm font-medium text-slate-200">{{ $title }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $desc }}</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model="{{ $model }}" class="sr-only peer">
                                    <span
                                        class="relative h-6 w-11 rounded-full bg-slate-700 peer-checked:bg-amber-500 transition-colors shrink-0 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-5"></span>
                                </label>
                            @endforeach

                            <div class="pt-3 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="h-11 px-5 rounded-xl bg-amber-600 hover:bg-amber-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2">
                                    <span wire:loading.remove wire:target="saveNotifications"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-save class="w-4 h-4" />
                                        Enregistrer
                                    </span>
                                    <span wire:loading wire:target="saveNotifications"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        Enregistrement…
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                @endif

                {{-- ═══ SÉCURITÉ ═══ --}}
                @if ($activeTab === 'security')
                    <section
                        class="rounded-2xl bg-[#0f1523] border border-white/[0.06] overflow-hidden shadow-xl shadow-black/10">
                        <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-500/20 flex items-center justify-center">
                                <x-lucide-shield class="w-4 h-4 text-rose-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Sécurité</h2>
                                <p class="text-[11px] text-slate-500">Accès, sessions et permissions</p>
                            </div>
                        </div>

                        <form wire:submit="saveSecurity" class="p-5 sm:p-6 space-y-5">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                        Durée d'inactivité avant déconnexion (minutes)
                                    </label>
                                    <input type="number" wire:model="session_lifetime" min="15"
                                        max="1440"
                                        class="w-full h-11 rounded-xl bg-[#070b14] border border-white/10 px-3.5 text-sm text-slate-200 focus:outline-none focus:border-rose-500/50 transition-all">
                                    @error('session_lifetime')
                                        <p class="mt-1 text-[11px] text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            {{-- Accès --}}
                            <div class="space-y-3">
                                <div x-data="{ enabled: $wire.entangle('force_2fa') }"
                                    class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                    @click="enabled = !enabled">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <x-lucide-key-round class="w-4 h-4 text-rose-400 shrink-0" />
                                        <div>
                                            <p class="text-sm font-medium text-slate-200">Forcer la 2FA</p>
                                            <p class="text-[11px] text-slate-500">Authentification à deux facteurs
                                                obligatoire</p>
                                        </div>
                                    </div>
                                    <span
                                        class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                        :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                        <span
                                            class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                            :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </span>
                                </div>

                                <div x-data="{ enabled: $wire.entangle('open_only_for_tenant') }"
                                    class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                    @click="enabled = !enabled">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <x-lucide-lock class="w-4 h-4 text-rose-400 shrink-0" />
                                        <div>
                                            <p class="text-sm font-medium text-slate-200">Accès restreint au
                                                sous-domaine</p>
                                            <p class="text-[11px] text-slate-500">Bloquer l'accès a tous les autres
                                                utilisateurs. </p>
                                            <p class="text-[11px] text-orange-500">Seul le directeur gardera son accès!
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                        :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                        <span
                                            class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                            :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </span>
                                </div>
                            </div>

                            {{-- Permissions tuteurs --}}
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                    Permissions des tuteurs</p>
                                <div class="space-y-3">
                                    <div x-data="{ enabled: $wire.entangle('tutors_can_see_bulletin') }"
                                        class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                        @click="enabled = !enabled">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <x-lucide-users class="w-4 h-4 text-sky-400 shrink-0" />
                                            <div>
                                                <p class="text-sm font-medium text-slate-200">Parents peuvent lire</p>
                                                <p class="text-[11px] text-slate-500">Autoriser les tuteurs à consulter
                                                    les bulletins</p>
                                            </div>
                                        </div>
                                        <span
                                            class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                            :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                            <span
                                                class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                                :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </span>
                                    </div>

                                    <div x-data="{ enabled: $wire.entangle('tutors_can_download_bulletin') }"
                                        class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                        @click="enabled = !enabled">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <x-lucide-download class="w-4 h-4 text-sky-400 shrink-0" />
                                            <div>
                                                <p class="text-sm font-medium text-slate-200">Parents peuvent
                                                    télécharger</p>
                                                <p class="text-[11px] text-slate-500">Autoriser les tuteurs à
                                                    télécharger les bulletins en PDF</p>
                                            </div>
                                        </div>
                                        <span
                                            class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                            :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                            <span
                                                class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                                :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Édition des coefficients --}}
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                    Édition des coefficients</p>
                                <div class="space-y-3">
                                    <div x-data="{ enabled: $wire.entangle('pp_can_edit_coef') }"
                                        class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                        @click="enabled = !enabled">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <x-lucide-pencil class="w-4 h-4 text-amber-400 shrink-0" />
                                            <div>
                                                <p class="text-sm font-medium text-slate-200">Professeurs principaux
                                                    (PP)
                                                </p>
                                                <p class="text-[11px] text-slate-500">Autoriser les PP à modifier les
                                                    coefficients de leur classe</p>
                                            </div>
                                        </div>
                                        <span
                                            class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                            :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                            <span
                                                class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                                :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </span>
                                    </div>

                                    <div x-data="{ enabled: $wire.entangle('ae_can_edit_coef') }"
                                        class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                        @click="enabled = !enabled">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <x-lucide-pencil class="w-4 h-4 text-amber-400 shrink-0" />
                                            <div>
                                                <p class="text-sm font-medium text-slate-200">Animateurs
                                                    d'établissement (AE)
                                                </p>
                                                <p class="text-[11px] text-slate-500">Autoriser les AE à modifier les
                                                    coefficients de leur matière</p>
                                            </div>
                                        </div>
                                        <span
                                            class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                            :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                            <span
                                                class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                                :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </span>
                                    </div>

                                    <div x-data="{ enabled: $wire.entangle('ca_can_edit_coef') }"
                                        class="flex items-center justify-between gap-4 rounded-xl bg-[#070b14] border border-white/[0.05] px-4 py-3.5 cursor-pointer"
                                        @click="enabled = !enabled">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <x-lucide-pencil class="w-4 h-4 text-amber-400 shrink-0" />
                                            <div>
                                                <p class="text-sm font-medium text-slate-200">Chefs d'atelier des
                                                    filières (CA)</p>
                                                <p class="text-[11px] text-slate-500">Autoriser les CA à modifier les
                                                    coefficients de leur filière</p>
                                            </div>
                                        </div>
                                        <span
                                            class="relative h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                                            :class="enabled ? 'bg-rose-500' : 'bg-slate-700'">
                                            <span
                                                class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200"
                                                :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-500 text-sm font-semibold text-white transition-all disabled:opacity-50 inline-flex items-center gap-2">
                                    <span wire:loading.remove wire:target="saveSecurity"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-save class="w-4 h-4" />
                                        Enregistrer
                                    </span>
                                    <span wire:loading wire:target="saveSecurity"
                                        class="inline-flex items-center gap-2">
                                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        Enregistrement…
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                @endif

            </div>
        </div>
    </div>
</div>

