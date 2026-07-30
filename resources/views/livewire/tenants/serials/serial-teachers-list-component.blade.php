<section class="mb-6 p-2 relative" x-data="{ open: true }">
    <div class="mx-auto w-full max-w-[1900px] px-3 sm:px-4 lg:px-6 xl:px-8 bg-slate-950 ">
        <div class="flex flex-wrap items-center gap-3 p-3 my-1.5 border-b border-b-slate-700 mb-6">
            <h1 class="text-lg sm:text-xl font-bold text-slate-400 px-3 py-2.5 uppercase">
                Les enseigants de la série <span class="font-mono text-amber-400 font-semibold">{{ $serial->name }}
                    <span>({{ $serial->code }})</span>
                </span>
            </h1>

            <span
                class="px-3 py-1 rounded-full @if ($serial->is_active) bg-emerald-500/10 text-emerald-400 @else bg-red-500/10 text-red-400 @endif text-xs">
                série {{ $serial->is_active ? 'active' : 'non active' }}
            </span>
        </div>

        <section>
            @livewire('tenants.components.teachers-lister-component', ['serial_id' => $this->serial->id, 'serial' => $this->serial])
        </section>
    </div>
</section>

