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
            class="inline-flex items-center justify-center px-4 py-3 bg-purple-800/45 hover:bg-purple-600 text-white hover:text-black rounded-2xl transition-colors">
            <span class="inline-flex items-center justify-center">
                <span class="inline-flex items-center gap-3">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Générer les notes en PDF</span>
                </span>
            </span>
        </a>

    </div>
    @livewire('tenants.components.classe-students-marks-lister-component', ['classe' => $this->classe, 'classe_slug' => $this->classe_slug])
</div>

