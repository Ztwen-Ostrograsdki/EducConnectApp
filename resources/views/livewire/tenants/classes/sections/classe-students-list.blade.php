<div class="min-h-screen bg-[#070a12] text-slate-100 pb-24">

    <div class="max-w-[1600px] mx-auto px-4 py-6 space-y-6">

        {{-- ========== ACTIONS ========== --}}
        <section class="flex flex-wrap items-center gap-2">
            <button wire:click="generateNewClasseStudentsList" wire:loading.attr="disabled"
                wire:target="generateNewClasseStudentsList"
                class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                           bg-sky-500/15 text-sky-400 border border-sky-500/25
                           hover:bg-sky-500 hover:text-white hover:border-sky-500
                           transition-all duration-200 disabled:opacity-50">
                <span wire:loading.remove wire:target="generateNewClasseStudentsList"
                    class="inline-flex items-center gap-2">
                    <x-lucide-file-down class="w-3.5 h-3.5" />
                    Exporter PDF
                </span>
                <span wire:loading wire:target="generateNewClasseStudentsList" class="inline-flex items-center gap-2">
                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                    Génération…
                </span>
            </button>

            <a wire:navigate href="{{ route('tenant.students.print.configuration', ['classe_slug' => $classe->slug]) }}"
                class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl text-xs font-medium
                      bg-indigo-500/15 text-indigo-400 border border-indigo-500/25
                      hover:bg-indigo-500 hover:text-white hover:border-indigo-500
                      transition-all duration-200">
                <x-lucide-printer class="w-3.5 h-3.5" />
                PDF personnalisé
            </a>
        </section>

        {{-- ========== LISTE ========== --}}
        <section>
            @livewire('tenants.components.students-lister-component', [
                'classe_id' => $classe->id,
                'classe' => $classe,
            ])
        </section>

    </div>
</div>

