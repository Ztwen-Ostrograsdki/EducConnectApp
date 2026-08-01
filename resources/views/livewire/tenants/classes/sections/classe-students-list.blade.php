<div class="min-h-screen bg-slate-950 text-slate-100 w-full max-w-full overflow-x-hidden pb-32">

    <div class="w-full max-w-[100vw] overflow-x-hidden">

        <section class="my-2">

            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <button wire:click='generateNewClasseStudentsList'
                    class="py-2.5 px-5 rounded-2xl bg-sky-500/50 hover:bg-sky-600/75 transition-all text-sm">
                    <span wire:loading.remove wire:target='generateNewClasseStudentsList'
                        class="inline-flex gap-x-2 items-center">
                        <x-lucide-save class="w-4 h-4" />
                        Exporter la liste en PDF
                    </span>
                    <span wire:loading wire:target='generateNewClasseStudentsList'
                        class="inline-flex items-center gap-x-2">
                        <span class="flex items-center gap-x-2.2">
                            <span>Processus en cours...</span>
                            <x-lucide-refresh-ccw class="w-4 h-4 animate-spin" />
                        </span>
                    </span>

                </button>
                <a wire:navigate
                    href="{{ route('tenant.students.print.configuration', ['classe_slug' => $classe->slug]) }}"
                    class="py-2 px-2 bg-indigo-700/40 hover:bg-indigo-800 text-white hover:text-black flex items-center gap-2 active:scale-95 rounded-2xl">
                    <x-lucide-printer class="w-4 h-4" />
                    <span>Génaration personnalisée de la liste en PDF</span>
                </a>
            </div>

        </section>

        <section>
            @livewire('tenants.components.students-lister-component', ['classe_id' => $classe->id, 'classe' => $classe])
        </section>
    </div>
</div>

