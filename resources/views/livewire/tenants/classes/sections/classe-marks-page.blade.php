<div>
    <div class="flex justify-end flex-wrap gap-3 bg-slate-950 p-2">

        <button
            wire:click="{{ $this->classe->is_locked ? 'unlockClasse(' . $this->classe->id . ')' : 'lockClasse(' . $this->classe->id . ')' }}"
            wire:loading.attr="disabled" wire:target="lockClasse, unlockClasse"
            class="relative text-white hover:text-black py-3 px-4 rounded-2xl {{ $this->classe->is_locked ? 'bg-emerald-600/20 hover:bg-emerald-500/50' : 'bg-red-500/60 hover:bg-red-600' }} transition-all font-medium">
            <span wire:loading.remove wire:target="lockClasse, unlockClasse"
                class="inline-flex items-center justify-center gap-3">
                <span class="inline-flex items-center justify-center gap-3">
                    @if ($this->classe->is_locked)
                        <x-lucide-lock-open class="w-4 h-4" />
                        <span>Déverrouiller </span>
                    @else
                        <x-lucide-lock class="w-4 h-4" />
                        <span>Verrouiller l'insertion des notes</span>
                    @endif
                </span>
            </span>
            <span wire:loading wire:loading wire:target="lockClasse, unlockClasse"
                class="inline-flex items-center gap-1">
                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
            </span>
        </button>

        <a wire:navigate href="{{ route('tenant.notes.print.configuration') }}"
            class="inline-flex items-center justify-center px-3 py-3 bg-purple-800/45 hover:bg-purple-600 text-white hover:text-black rounded-2xl transition-colors">
            <span class="inline-flex items-center justify-center">
                <span class="inline-flex items-center gap-3">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Générer les notes en PDF</span>
                </span>
            </span>
        </a>
        <button type="button" wire:click="shareClasseStudentsNotes({{ $this->classe->id }})"
            wire:loading.attr="disabled" wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
            class="flex items-center gap-2.5 py-3 px-3.5 rounded-xl bg-orange-500/90 hover:bg-orange-500/50 text-black text-xs hover:text-black font-medium transition-all disabled:opacity-50 active:scale-[0.97] animate-pulse">
            <span wire:loading.remove wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
                class="inline-flex items-center gap-2.5 truncate">
                <x-lucide-send class="w-4 h-4 shrink-0" />
                Envoyer les notes disponibles aux parents
            </span>
            <span wire:loading wire:target="shareClasseStudentsNotes({{ $this->classe->id }})"
                class="inline-flex items-center gap-2">
                <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
            </span>
        </button>

    </div>
    @livewire('tenants.components.classe-students-marks-lister-component', ['classe' => $this->classe, 'classe_slug' => $this->classe_slug])
</div>

