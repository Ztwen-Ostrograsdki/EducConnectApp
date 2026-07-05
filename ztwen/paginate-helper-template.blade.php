<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation">
            <span>
                @if ($paginator->onFirstPage())
                    <span>Previous</span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev">Previous</button>
                @endif
            </span>

            <span>
                @if ($paginator->onLastPage())
                    <span>Next</span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next">Next</button>
                @endif
            </span>
        </nav>
    @endif
</div>
<section class="my-2.5">
    <div
        class="space-y-6 min-w-0 grid
                        grid-cols-2
                        xl:grid-cols-2
                        gap-4">

        {{-- STATS --}}
        <div
            class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

            <h2 class="text-lg font-semibold">

                Activité des Parents

            </h2>

            <div class="mt-5 space-y-5">

                @foreach ([['Bulletins consultés', '78%', 'bg-indigo-500'], ['Notifications lues', '69%', 'bg-emerald-500'], ['Parents connectés', '57%', 'bg-sky-500'], ['Accès bloqués', '8%', 'bg-rose-500']] as $item)
                    <div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-slate-300">
                                {{ $item[0] }}
                            </span>

                            <span class="text-sm font-semibold">
                                {{ $item[1] }}
                            </span>

                        </div>

                        <div
                            class="mt-2 h-2 rounded-full
                                            bg-slate-800 overflow-hidden">

                            <div class="h-full rounded-full {{ $item[2] }}" style="width: {{ $item[1] }}">
                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- RECENT --}}
        <div
            class="rounded-3xl
                                border border-slate-800
                                bg-slate-900
                                p-5">

            <h2 class="text-lg font-semibold">

                Activités Récentes

            </h2>

            <div class="mt-5 space-y-4">

                @foreach (range(1, 3) as $activity)
                    <div
                        class="rounded-2xl
                                        bg-slate-950
                                        p-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="w-11 h-11 rounded-2xl
                                                bg-indigo-500/10
                                                flex items-center
                                                justify-center
                                                text-indigo-400">

                                ✓

                            </div>

                            <div class="min-w-0">

                                <h3 class="font-medium text-sm">

                                    Bulletin envoyé

                                </h3>

                                <p class="mt-1 text-sm text-slate-400">

                                    Bulletin transmis au parent

                                </p>

                                <p class="mt-2 text-xs text-slate-500">

                                    Il y a 35 min

                                </p>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
</section>
