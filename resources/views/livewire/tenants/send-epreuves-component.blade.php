<div class="min-h-screen bg-[#070b14] text-slate-100" wire:key="send-epreuves">
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-6 pb-32">

        {{-- ── Header ─────────────────────────────────────────────────── --}}
        <header
            class="relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 via-[#0c1525] to-[#0a1220] px-6 py-8 sm:px-8 shadow-2xl shadow-black/40">
            {{-- glow orbs --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-cyan-500/15 blur-3xl"></div>
                <div class="absolute -left-12 bottom-0 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
                <div class="absolute right-1/3 top-1/2 h-24 w-24 rounded-full bg-sky-400/5 blur-2xl"></div>
            </div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 ring-1 ring-cyan-400/30 backdrop-blur-sm">
                        <x-lucide-upload-cloud class="h-6 w-6 text-cyan-400" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white">
                            Envoi des épreuves
                        </h1>
                        <p class="mt-1 max-w-md text-sm text-slate-400">
                            Déposez plusieurs fichiers, renseignez le contexte pédagogique, puis lancez l’envoi.
                        </p>
                    </div>
                </div>

                @if ($this->filesCount > 0)
                    <div
                        class="inline-flex items-center gap-2 self-start rounded-full border border-cyan-500/20 bg-cyan-500/10 px-4 py-2 backdrop-blur-sm">
                        <x-lucide-files class="h-4 w-4 text-cyan-400" />
                        <span class="text-sm font-medium text-cyan-300">
                            {{ $this->filesCount }} fichier{{ $this->filesCount > 1 ? 's' : '' }}
                        </span>
                    </div>
                @endif
            </div>
        </header>

        <form wire:submit="send" class="space-y-6">

            {{-- ── Contexte pédagogique ───────────────────────────────── --}}
            {{-- Pas d'overflow-hidden ici : les menus WireUI (x-select) doivent pouvoir déborder --}}
            <section class="relative z-20 rounded-2xl border border-white/5 bg-[#0c1220] shadow-xl shadow-black/30">
                <div class="flex items-center gap-3 border-b border-white/5 px-5 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-400 ring-1 ring-cyan-500/20">
                        <x-lucide-graduation-cap class="h-4.5 w-4.5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-100">
                            Contexte pédagogique
                        </h2>
                        <p class="text-xs text-slate-500">
                            Classe, filière, période… ces infos seront liées aux fichiers.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Classe --}}
                    <div class="relative z-20 space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-users class="h-3.5 w-3.5 text-slate-500" />
                            Classe
                        </label>
                        <x-select placeholder="Sélectionner une classe" wire:model.live="classe_id" option-label="name"
                            option-value="id" :options="$this->classes" option="bottom-start" />
                    </div>

                    {{-- Filière --}}
                    <div class="relative z-20 space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-git-branch class="h-3.5 w-3.5 text-slate-500" />
                            Filière
                        </label>
                        <x-select placeholder="Sélectionner une filière" wire:model.live="filiar_id" option-label="name"
                            option-value="id" :options="$this->filiars" option="bottom-start" />
                    </div>

                    {{-- Promotion --}}
                    <div class="relative z-20 space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-award class="h-3.5 w-3.5 text-slate-500" />
                            Promotion
                        </label>
                        <x-select placeholder="Sélectionner une promotion" wire:model.live="promotion_id"
                            option-label="name" option-value="id" :options="$this->promotions" option="bottom-start" />
                    </div>

                    {{-- Matière --}}
                    <div class="relative z-10 space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-book-open class="h-3.5 w-3.5 text-slate-500" />
                            Matière
                        </label>
                        <x-select placeholder="Sélectionner une matière" wire:model.live="subject_id"
                            option-label="name" option-value="id" :options="$this->subjects" option="bottom-start" />
                    </div>

                    {{-- Période --}}
                    <div class="relative z-10 space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-calendar-days class="h-3.5 w-3.5 text-slate-500" />
                            Période
                        </label>
                        <x-select placeholder="Sélectionner une période" wire:model.live="period" option="bottom-start">
                            @foreach ($this->periods as $p)
                                <x-select.option :value="$p['index']" :label="$p['label']" />
                            @endforeach
                        </x-select>
                        @error('period')
                            <p class="mt-1 flex items-center gap-1 text-xs text-rose-400">
                                <x-lucide-alert-circle class="h-3.5 w-3.5" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="relative space-y-1.5">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                            <x-lucide-tag class="h-3.5 w-3.5 text-slate-500" />
                            Type d’épreuve
                        </label>
                        <x-input placeholder="Ex : Devoir-1, Interro-2, Examen…" wire:model="type" />
                        @error('type')
                            <p class="mt-1 flex items-center gap-1 text-xs text-rose-400">
                                <x-lucide-alert-circle class="h-3.5 w-3.5" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ── Zone d’upload ──────────────────────────────────────── --}}
            <section
                class="relative z-10 overflow-hidden rounded-2xl border border-white/5 bg-[#0c1220] shadow-xl shadow-black/30">
                <div class="flex items-center gap-3 border-b border-white/5 px-5 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400 ring-1 ring-indigo-500/20">
                        <x-lucide-folder-up class="h-4.5 w-4.5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-100">
                            Fichiers à envoyer
                        </h2>
                        <p class="text-xs text-slate-500">
                            PDF, Word, Excel, images, archives… · Max 10 Mo / fichier · 30 fichiers max
                        </p>
                    </div>
                </div>

                <div class="space-y-5 p-5">
                    {{-- Drop zone --}}
                    <div class="group relative overflow-hidden rounded-xl border-2 border-dashed border-slate-700/80 bg-[#080e1a] transition-all duration-200
                               hover:border-cyan-500/50 hover:bg-cyan-500/[0.04]"
                        x-data="{ dragging: false }" x-on:dragenter.prevent="dragging = true"
                        x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
                        x-on:drop.prevent="
                            dragging = false;
                            const files = $event.dataTransfer.files;
                            if (!files || files.length === 0) return;
                            // API Livewire officielle pour l'upload multiple par drag & drop
                            $wire.uploadMultiple('files', files);
                        "
                        x-bind:class="dragging
                            ?
                            'border-cyan-400 bg-cyan-500/10 scale-[1.01] shadow-lg shadow-cyan-500/10' :
                            ''">
                        {{-- Input masqué : sélection classique via le label --}}
                        <input type="file" id="epreuves-files" class="sr-only" wire:model="files" multiple
                            accept=".pdf,.doc,.docx,.odt,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar" />

                        {{-- Zone cliquable --}}
                        <label for="epreuves-files"
                            class="flex cursor-pointer flex-col items-center justify-center gap-3 px-6 py-12 text-center">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500/20 to-indigo-600/20 text-cyan-400 ring-1 ring-cyan-400/30 transition group-hover:scale-105 group-hover:from-cyan-500/30 group-hover:to-indigo-600/30">
                                <x-lucide-cloud-upload class="h-7 w-7" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-200">
                                    Glissez-déposez vos fichiers ici
                                </p>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    ou <span class="font-semibold text-cyan-400">parcourez</span> votre ordinateur
                                </p>
                            </div>
                        </label>

                        {{-- Loading overlay --}}
                        <div wire:loading wire:target="files"
                            class="absolute inset-0 z-20 flex items-center justify-center rounded-xl bg-[#070b14]/90 backdrop-blur-sm">
                            <div class="flex items-center gap-3 text-sm font-medium text-cyan-400">
                                <x-lucide-loader-2 class="h-5 w-5 animate-spin" />
                                Chargement des fichiers…
                            </div>
                        </div>
                    </div>

                    @error('files')
                        <p class="flex items-center gap-1.5 text-xs text-rose-400">
                            <x-lucide-alert-circle class="h-3.5 w-3.5 shrink-0" />
                            {{ $message }}
                        </p>
                    @enderror
                    @error('files.*')
                        <p class="flex items-center gap-1.5 text-xs text-rose-400">
                            <x-lucide-alert-circle class="h-3.5 w-3.5 shrink-0" />
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Liste des fichiers --}}
                    @if ($this->filesCount > 0)
                        <div class="space-y-3" wire:key="files-list-{{ $this->filesCount }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-cyan-500/15 px-2 text-xs font-semibold text-cyan-300 ring-1 ring-cyan-500/25">
                                        {{ $this->filesCount }}
                                    </span>
                                    <span class="text-sm font-medium text-slate-300">
                                        fichier{{ $this->filesCount > 1 ? 's' : '' }}
                                        prêt{{ $this->filesCount > 1 ? 's' : '' }}
                                    </span>
                                </div>
                                <button type="button" wire:click="clearFiles"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-medium text-rose-400 transition hover:bg-rose-500/10">
                                    <x-lucide-trash-2 class="h-3.5 w-3.5" />
                                    Tout retirer
                                </button>
                            </div>

                            <ul
                                class="divide-y divide-white/5 overflow-hidden rounded-xl border border-white/5 bg-[#080e1a]">
                                @foreach ($this->filesList as $item)
                                    <li wire:key="file-row-{{ $item['index'] }}"
                                        class="group flex items-center gap-3 px-4 py-3 transition hover:bg-white/[0.03]">
                                        {{-- Icône --}}
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-800/80 text-slate-400 ring-1 ring-white/5">
                                            <x-lucide-file class="h-5 w-5" />
                                        </div>

                                        {{-- Infos --}}
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-medium text-slate-200"
                                                title="{{ $item['name'] }}">
                                                {{ $item['name'] }}
                                            </p>
                                            <div
                                                class="mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-slate-500">
                                                <span class="inline-flex items-center gap-1">
                                                    <x-lucide-hard-drive class="h-3 w-3" />
                                                    {{ $item['size'] }}
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <x-lucide-file-type class="h-3 w-3" />
                                                    {{ $item['type'] }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Retirer --}}
                                        <button type="button" wire:click="removeFile({{ $item['index'] }})"
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-500 opacity-60 transition hover:bg-rose-500/10 hover:text-rose-400 group-hover:opacity-100"
                                            title="Retirer ce fichier">
                                            <x-lucide-x class="h-4 w-4" />
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ── Actions ────────────────────────────────────────────── --}}
            <div class="flex flex-col-reverse items-stretch gap-3 sm:flex-row sm:items-center sm:justify-end">
                <x-button type="button" secondary wire:click="clearFiles" wire:loading.attr="disabled"
                    class="justify-center">
                    <span class="inline-flex items-center gap-2">
                        <x-lucide-rotate-ccw class="h-4 w-4" />
                        Réinitialiser
                    </span>
                </x-button>

                <x-button type="submit" primary wire:loading.attr="disabled" wire:target="send"
                    class="min-w-[180px] justify-center">
                    <span wire:loading.remove wire:target="send" class="inline-flex items-center gap-2">
                        <x-lucide-send class="h-4 w-4" />
                        Envoyer les épreuves
                    </span>
                    <span wire:loading wire:target="send" class="inline-flex items-center gap-2">
                        <x-lucide-loader-2 class="h-4 w-4 animate-spin" />
                        Envoi en cours…
                    </span>
                </x-button>
            </div>
        </form>
    </div>
</div>

