<div class="w-full overflow-x-hidden">

    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8 bg-slate-950 ">
        <div class="flex flex-wrap items-center gap-3 p-3 my-1.5 border-b border-b-slate-700 mb-6">
            <h1 class="text-lg sm:text-xl font-bold text-slate-400 px-3 py-2.5 uppercase">
                Les apprenants de la filière <span class="font-mono text-amber-400 font-semibold">{{ $filiar->name }}
                    <span>({{ $filiar->code }})</span>
                </span>
            </h1>

            <span
                class="px-3 py-1 rounded-full @if ($filiar->is_active) bg-emerald-500/10 text-emerald-400 @else bg-red-500/10 text-red-400 @endif text-xs">
                filière {{ $filiar->is_active ? 'active' : 'non active' }}
            </span>
        </div>

        <section>
            @livewire('tenants.components.students-lister-component', ['filiar_id' => $this->filiar->id, 'filiar' => $this->filiar])
        </section>
    </div>
</div>

