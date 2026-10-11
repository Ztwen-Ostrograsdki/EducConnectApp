<div class="mx-auto w-full py-6.5 space-y-6">

    {{-- ===================== EN-TÊTE ===================== --}}
    <div
        class="relative overflow-hidden rounded-2xl border border-white/[0.08] bg-gradient-to-br from-[#0c1019] via-[#0c1019] to-cyan-950/30 p-5 sm:p-6">
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-8 -bottom-8 h-32 w-32 rounded-full bg-violet-500/10 blur-3xl pointer-events-none">
        </div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-500/15 border border-cyan-500/20">
                    <x-lucide-clock-3 class="w-5 h-5 text-cyan-400" />
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-white tracking-tight">
                        Heures d'Animation Pédagogique
                    </h1>
                    @if ($subject)
                        <p class="mt-1 text-sm text-slate-400 flex items-center gap-1.5 flex-wrap">
                            <x-lucide-book-open class="w-3.5 h-3.5 text-sky-400 shrink-0" />
                            <span class="font-medium text-cyan-300">{{ $subject->name }}</span>
                            <span class="text-slate-600">·</span>
                            <span class="text-slate-500 font-mono text-xs">{{ $subject->code }}</span>
                        </p>
                    @endif
                    @if ($this->activeYear)
                        <p class="mt-1 text-xs text-slate-500 flex items-center gap-1.5">
                            <x-lucide-calendar-range class="w-3.5 h-3.5 text-violet-400 shrink-0" />
                            Année active :
                            <span class="text-slate-400">{{ $this->activeYear->slug ?? $this->activeYear->name }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a wire:navigate href="{{ route('tenant.subjects.portal') }}"
                    class="h-10 px-3.5 rounded-xl text-sm inline-flex items-center gap-1.5 font-medium
                           border border-white/[0.08] text-slate-400
                           hover:bg-white/[0.06] hover:text-white hover:border-white/[0.12]
                           transition-all duration-200">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    <span class="hidden sm:inline">Retour</span>
                </a>

                <button type="button" wire:click="openCreateAPTimeForm" wire:loading.attr="disabled"
                    wire:target="openCreateAPTimeForm"
                    class="h-10 px-4 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white
                           shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/40
                           transition-all duration-200 inline-flex items-center gap-1.5
                           disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="openCreateAPTimeForm"
                        class="inline-flex items-center gap-1.5">
                        <x-lucide-plus class="w-4 h-4" />
                        Nouveau créneau
                    </span>
                    <span wire:loading wire:target="openCreateAPTimeForm" class="inline-flex items-center gap-1.5">
                        <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                        Ouverture…
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== FILTRES ===================== --}}
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        {{-- Recherche --}}
        <div class="relative flex-1 max-w-md group">
            <x-lucide-search
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500
                                   group-focus-within:text-cyan-400 transition-colors duration-200" />
            <input wire:model.live.debounce.300ms="search" type="search"
                placeholder="Rechercher (libellé, notes, matière…)"
                class="w-full h-10 pl-9 pr-9 rounded-xl border border-white/[0.08] bg-[#0c1019] text-sm text-white
                       placeholder:text-slate-600
                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                       transition-all duration-200" />
            <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                <x-lucide-loader-2 class="w-4 h-4 text-cyan-400 animate-spin" />
            </div>
        </div>

        {{-- Filtres statut --}}
        <div class="flex rounded-xl border border-white/[0.08] overflow-hidden bg-[#0c1019] p-0.5">
            <button type="button" wire:click="$set('is_active_filter', 'actives')" wire:loading.attr="disabled"
                class="h-9 px-3.5 text-xs font-medium rounded-lg transition-all duration-200 inline-flex items-center gap-1.5
                       {{ $is_active_filter === 'actives'
                           ? 'bg-emerald-500/20 text-emerald-400 shadow-sm'
                           : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                <x-lucide-check-circle class="w-3.5 h-3.5" />
                Actifs
                <span class="ml-0.5 tabular-nums opacity-70">({{ $this->activesCount }})</span>
            </button>
            <button type="button" wire:click="$set('is_active_filter', 'inactives')" wire:loading.attr="disabled"
                class="h-9 px-3.5 text-xs font-medium rounded-lg transition-all duration-200 inline-flex items-center gap-1.5
                       {{ $is_active_filter === 'inactives'
                           ? 'bg-amber-500/20 text-amber-400 shadow-sm'
                           : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                <x-lucide-pause-circle class="w-3.5 h-3.5" />
                Inactifs
                <span class="ml-0.5 tabular-nums opacity-70">({{ $this->inactivesCount }})</span>
            </button>
            <button type="button" wire:click="$set('is_active_filter', 'all')" wire:loading.attr="disabled"
                class="h-9 px-3.5 text-xs font-medium rounded-lg transition-all duration-200 inline-flex items-center gap-1.5
                       {{ $is_active_filter === 'all'
                           ? 'bg-sky-500/20 text-sky-400 shadow-sm'
                           : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                <x-lucide-list class="w-3.5 h-3.5" />
                Tous
            </button>
        </div>
    </div>

    {{-- ===================== FORMULAIRE ===================== --}}
    @if ($showAPTimeForm)
        <div class="w-full max-w-xl mx-auto" x-data x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'nearest' })" x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-3 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="border border-white/[0.08] bg-[#0c1019] shadow-2xl shadow-black/40 rounded-2xl overflow-hidden">

                {{-- Header formulaire --}}
                <div
                    class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]
                            bg-gradient-to-r from-cyan-500/5 to-transparent">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg
                                    {{ $ap_time_id ? 'bg-amber-500/15 border border-amber-500/20' : 'bg-cyan-500/15 border border-cyan-500/20' }}">
                            @if ($ap_time_id)
                                <x-lucide-pencil class="w-4 h-4 text-amber-400" />
                            @else
                                <x-lucide-plus-circle class="w-4 h-4 text-cyan-400" />
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">
                                {{ $ap_time_id ? 'Modifier le créneau AP' : 'Nouveau créneau AP' }}
                            </h3>
                            @if ($subject)
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $subject->code }} — {{ $subject->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <button type="button" wire:click="resetAPTimeForm"
                        class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/[0.08]
                               transition-all duration-150">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>

                <form wire:submit="saveAPTime" class="p-5 space-y-4">
                    {{-- Matière --}}
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-book-open class="w-3.5 h-3.5 text-sky-400" />
                            Matière
                        </label>
                        <select @disabled($subject || $subject_slug) wire:model="subject_id" required
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                   focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                   transition-all duration-200
                                   disabled:bg-slate-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                            <option value="">Choisir une matière</option>
                            @foreach ($this->subjects as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->name }} — ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jour --}}
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-calendar-days class="w-3.5 h-3.5 text-violet-400" />
                            Jour
                        </label>
                        <select wire:model="day_of_week" required
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                   focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                   transition-all duration-200">
                            @foreach (config('timeplan.days') as $num => $dayLabel)
                                <option value="{{ $num }}">{{ $dayLabel }}</option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Heures --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-clock class="w-3.5 h-3.5 text-emerald-400" />
                                Début
                            </label>
                            <input wire:model="starts_at" type="time" required min="07:00" max="19:00"
                                step="60"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                       transition-all duration-200" />
                            @error('starts_at')
                                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                    <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                                <x-lucide-clock class="w-3.5 h-3.5 text-rose-400" />
                                Fin
                            </label>
                            <input wire:model="ends_at" type="time" required min="07:00" max="19:00"
                                step="60"
                                class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                       focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                       transition-all duration-200" />
                            @error('ends_at')
                                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                    <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Aide horaires --}}
                    <div
                        class="flex items-start gap-2 rounded-xl bg-white/[0.03] border border-white/[0.05] px-3 py-2.5">
                        <x-lucide-info class="w-3.5 h-3.5 text-slate-500 mt-0.5 shrink-0" />
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Horaires autorisés entre
                            <strong class="text-slate-400">07h00</strong> et
                            <strong class="text-slate-400">19h00</strong>.
                            L’heure de fin doit être strictement postérieure à l’heure de début.
                        </p>
                    </div>

                    {{-- Libellé --}}
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-type class="w-3.5 h-3.5 text-amber-400" />
                            Libellé
                            <span class="text-slate-600 normal-case font-normal">(facultatif)</span>
                        </label>
                        <input wire:model="label" type="text" maxlength="150" placeholder="Ex. Salle B12, TP…"
                            class="w-full h-10 rounded-xl border border-white/[0.08] bg-[#070a12] px-3 text-sm text-white
                                   placeholder:text-slate-600
                                   focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                   transition-all duration-200" />
                        @error('label')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label
                            class="flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-500 mb-1.5">
                            <x-lucide-align-left class="w-3.5 h-3.5 text-slate-500" />
                            Notes
                            <span class="text-slate-600 normal-case font-normal">(facultatif)</span>
                        </label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full rounded-xl border border-white/[0.08] bg-[#070a12] px-3 py-2.5 text-sm text-white
                                   focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 focus:outline-none
                                   transition-all duration-200 resize-none"></textarea>
                        @error('notes')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <x-lucide-alert-circle class="w-3.5 h-3.5 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Actif --}}
                    <label class="flex items-center gap-2.5 cursor-pointer group select-none">
                        <div class="relative">
                            <input type="checkbox" wire:model="is_active" class="peer sr-only" id="ap_is_active" />
                            <div
                                class="h-5 w-9 rounded-full bg-slate-700 peer-checked:bg-cyan-500 transition-colors duration-200">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow
                                        peer-checked:translate-x-4 transition-transform duration-200">
                            </div>
                        </div>
                        <span class="text-sm text-slate-300 group-hover:text-white transition-colors duration-150">
                            Créneau actif
                        </span>
                    </label>

                    {{-- Actions formulaire --}}
                    <div class="flex justify-end gap-2 pt-2 border-t border-white/[0.05]">
                        <button type="button" wire:click="resetAPTimeForm" wire:loading.attr="disabled"
                            wire:target="saveAPTime"
                            class="h-10 px-4 rounded-xl text-sm font-medium border border-white/[0.08] text-slate-400
                                   hover:bg-white/[0.06] hover:text-white
                                   transition-all duration-200 disabled:opacity-50">
                            Annuler
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveAPTime"
                            class="h-10 px-5 rounded-xl text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-white
                                   shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/40
                                   transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed
                                   inline-flex items-center gap-2 min-w-[140px] justify-center">
                            <span wire:loading.remove wire:target="saveAPTime"
                                class="inline-flex items-center gap-1.5">
                                @if ($ap_time_id)
                                    <x-lucide-check class="w-4 h-4" />
                                    Mettre à jour
                                @else
                                    <x-lucide-save class="w-4 h-4" />
                                    Enregistrer
                                @endif
                            </span>
                            <span wire:loading wire:target="saveAPTime" class="inline-flex items-center gap-1.5">
                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                Enregistrement…
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ===================== LISTE ===================== --}}
    <div class="border border-white/[0.08] rounded-2xl overflow-hidden bg-[#0c1019] relative"
        wire:loading.class="opacity-60 pointer-events-none"
        wire:target="search, is_active_filter, deleteAPTime, activateAPTime, deactivateAPTime, saveAPTime">

        {{-- Overlay loading global liste --}}
        <div wire:loading.flex wire:target="search, is_active_filter"
            class="absolute inset-0 z-10 items-center justify-center bg-[#0c1019]/60 backdrop-blur-[1px]">
            <div
                class="flex items-center gap-2 rounded-xl bg-[#070a12] border border-white/[0.08] px-4 py-2.5 shadow-xl">
                <x-lucide-loader-2 class="w-4 h-4 text-cyan-400 animate-spin" />
                <span class="text-xs text-slate-300">Chargement…</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="text-[11px] uppercase tracking-wider text-slate-500 border-b border-white/[0.06] bg-white/[0.02]">
                    <tr>
                        <th class="px-4 py-3 font-medium">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-calendar-days class="w-3.5 h-3.5" /> Jour
                            </span>
                        </th>
                        <th class="px-4 py-3 font-medium">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-clock class="w-3.5 h-3.5" /> Horaires
                            </span>
                        </th>
                        <th class="px-4 py-3 font-medium">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-timer class="w-3.5 h-3.5" /> Durée
                            </span>
                        </th>
                        @unless ($subject)
                            <th class="px-4 py-3 font-medium">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-lucide-book-open class="w-3.5 h-3.5" /> Matière
                                </span>
                            </th>
                        @endunless
                        <th class="px-4 py-3 font-medium">
                            <span class="inline-flex items-center gap-1.5">
                                <x-lucide-tag class="w-3.5 h-3.5" /> Libellé
                            </span>
                        </th>
                        <th class="px-4 py-3 font-medium">Statut</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($this->apTimes as $apTime)
                        <tr class="group hover:bg-white/[0.025] transition-colors duration-150"
                            wire:key="ap-time-{{ $apTime->id }}">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 font-medium text-white">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-md bg-violet-500/15 text-[10px] font-semibold text-violet-300">
                                        {{ strtoupper(mb_substr($apTime->day_name, 0, 2)) }}
                                    </span>
                                    {{ $apTime->day_name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 tabular-nums">
                                    <span class="text-emerald-400 font-medium">{{ $apTime->start }}</span>
                                    <x-lucide-arrow-right class="w-3 h-3 text-slate-600" />
                                    <span class="text-rose-400 font-medium">{{ $apTime->end }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-400 tabular-nums">
                                {{ $apTime->duration }}&nbsp;h
                            </td>
                            @unless ($subject)
                                <td class="px-4 py-3">
                                    <span class="text-slate-300">{{ $apTime->subject?->name ?? '—' }}</span>
                                    @if ($apTime->subject?->code)
                                        <span
                                            class="block text-[11px] text-slate-600 font-mono">{{ $apTime->subject->code }}</span>
                                    @endif
                                </td>
                            @endunless
                            <td class="px-4 py-3 text-slate-400 max-w-[140px] truncate" title="{{ $apTime->label }}">
                                {{ $apTime->label ?: '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($apTime->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                 bg-emerald-500/12 text-emerald-400 border border-emerald-500/20">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span
                                                class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60 animate-ping"></span>
                                            <span
                                                class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        </span>
                                        Actif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                 bg-slate-500/12 text-slate-400 border border-slate-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center justify-end gap-0.5 opacity-70 group-hover:opacity-100 transition-opacity duration-150">
                                    {{-- Éditer --}}
                                    <button type="button" wire:click="initAPTimeData({{ $apTime->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="initAPTimeData({{ $apTime->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10
                                               transition-all duration-150 disabled:opacity-40"
                                        title="Modifier">
                                        <span wire:loading.remove wire:target="initAPTimeData({{ $apTime->id }})">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </span>
                                        <span wire:loading wire:target="initAPTimeData({{ $apTime->id }})">
                                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        </span>
                                    </button>

                                    {{-- Activer / Désactiver --}}
                                    @if ($apTime->is_active)
                                        <button type="button" wire:click="deactivateAPTime({{ $apTime->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="deactivateAPTime({{ $apTime->id }})"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10
                                                   transition-all duration-150 disabled:opacity-40"
                                            title="Désactiver">
                                            <span wire:loading.remove
                                                wire:target="deactivateAPTime({{ $apTime->id }})">
                                                <x-lucide-pause-circle class="w-4 h-4" />
                                            </span>
                                            <span wire:loading wire:target="deactivateAPTime({{ $apTime->id }})">
                                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                            </span>
                                        </button>
                                    @else
                                        <button type="button" wire:click="activateAPTime({{ $apTime->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="activateAPTime({{ $apTime->id }})"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10
                                                   transition-all duration-150 disabled:opacity-40"
                                            title="Réactiver">
                                            <span wire:loading.remove
                                                wire:target="activateAPTime({{ $apTime->id }})">
                                                <x-lucide-play-circle class="w-4 h-4" />
                                            </span>
                                            <span wire:loading wire:target="activateAPTime({{ $apTime->id }})">
                                                <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                            </span>
                                        </button>
                                    @endif

                                    {{-- Supprimer --}}
                                    <button type="button" wire:click="deleteAPTime({{ $apTime->id }})"
                                        wire:loading.attr="disabled" wire:target="deleteAPTime({{ $apTime->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10
                                               transition-all duration-150 disabled:opacity-40"
                                        title="Supprimer">
                                        <span wire:loading.remove wire:target="deleteAPTime({{ $apTime->id }})">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </span>
                                        <span wire:loading wire:target="deleteAPTime({{ $apTime->id }})">
                                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $subject ? 6 : 7 }}" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/[0.03] border border-white/[0.06]">
                                        <x-lucide-calendar-x class="w-7 h-7 text-slate-600" />
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-400 font-medium">Aucun créneau AP trouvé</p>
                                        <p class="text-xs text-slate-600 mt-1">Créez le premier créneau pour cette
                                            matière.</p>
                                    </div>
                                    <button type="button" wire:click="openCreateAPTimeForm"
                                        class="mt-1 inline-flex items-center gap-1.5 text-sm text-cyan-400 hover:text-cyan-300
                                               transition-colors duration-150">
                                        <x-lucide-plus class="w-4 h-4" />
                                        Créer un créneau
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->apTimes->hasPages())
            <div class="px-4 py-3 border-t border-white/[0.06] bg-white/[0.01]">
                {{ $this->apTimes->links() }}
            </div>
        @endif
    </div>
</div>

