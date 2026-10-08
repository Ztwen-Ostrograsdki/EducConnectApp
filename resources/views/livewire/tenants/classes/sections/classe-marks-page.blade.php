<div>
    {{-- ========== ACTIONS ========== --}}
    <div class="flex flex-wrap items-center justify-end gap-2 mb-6">

        {{-- Verrouiller / Déverrouiller --}}
        <button
            wire:click="{{ $this->classe->is_locked ? 'unlockClasse(' . $this->classe->id . ')' : 'lockClasse(' . $this->classe->id . ')' }}"
            wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                   transition-all duration-200 disabled:opacity-50
                   {{ $this->classe->is_locked
                       ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'
                       : 'bg-rose-500/15 text-rose-400 border border-rose-500/25 hover:bg-rose-500 hover:text-white hover:border-rose-500' }}">
            <span wire:loading.remove wire:target="lockClasse, unlockClasse" class="inline-flex items-center gap-2">
                @if ($this->classe->is_locked)
                    <x-lucide-lock-open class="w-3.5 h-3.5" />
                    Déverrouiller
                @else
                    <x-lucide-lock class="w-3.5 h-3.5" />
                    Verrouiller l’insertion
                @endif
            </span>
            <span wire:loading wire:target="lockClasse, unlockClasse">
                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
            </span>
        </button>

        {{-- PDF --}}
        <a wire:navigate href="{{ route('tenant.notes.print.configuration') }}"
            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                  bg-violet-500/15 text-violet-400 border border-violet-500/25
                  hover:bg-violet-500 hover:text-white hover:border-violet-500
                  transition-all duration-200">
            <x-lucide-printer class="w-3.5 h-3.5" />
            Générer PDF
        </a>

        {{-- Envoyer aux parents --}}
        <button type="button" wire:click="shareClasseStudentsNotes({{ $this->classe->id }})"
            wire:loading.attr="disabled" wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
            class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                       bg-amber-500/15 text-amber-400 border border-amber-500/25
                       hover:bg-amber-500 hover:text-white hover:border-amber-500
                       transition-all duration-200 disabled:opacity-50 active:scale-[0.97]">
            <span wire:loading.remove wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
                class="inline-flex items-center gap-2">
                <x-lucide-send class="w-3.5 h-3.5" />
                Envoyer aux parents
            </span>
            <span wire:loading wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
                class="inline-flex items-center gap-2">
                <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                Envoi…
            </span>
        </button>
    </div>

    @livewire('tenants.components.classe-students-marks-lister-component', [
        'classe' => $this->classe,
        'classe_slug' => $this->classe_slug,
    ])
</div>
