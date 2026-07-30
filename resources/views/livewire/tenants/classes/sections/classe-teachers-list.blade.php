<div class="w-full max-w-full overflow-x-hidden">
    <section class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- LEFT --}}
            <div class="min-w-0 p-3">

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-lg sm:text-xl font-bold break-words text-slate-300">
                        Enseignants de la {{ $this->classe->code }}
                    </h1>

                </div>

                <p class="mt-2 text-slate-400 text-sm sm:text-base">

                    Gestion des professeurs.

                </p>

            </div>

        </div>
    </section>
    <section>
        @livewire('tenants.components.teachers-lister-component', ['classe_id' => $this->classe->id, 'classe' => $this->classe])
    </section>

</div>

