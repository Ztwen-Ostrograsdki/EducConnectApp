<div class="w-full max-w-full overflow-x-hidden">

    <div class="min-h-[60vh] flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center space-y-5">

            {{-- Icône --}}
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl
                    bg-amber-500/10 border border-amber-500/25 mx-auto">
                <x-lucide-calendar-off class="w-8 h-8 text-amber-400" />
            </div>

            {{-- Badge --}}
            <div>
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium
                         bg-amber-500/15 text-amber-400 border border-amber-500/25">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    Temporairement indisponible
                </span>
            </div>

            {{-- Texte --}}
            <div class="space-y-2">
                <h2 class="text-xl font-bold text-white tracking-tight">
                    Présence des apprenants
                </h2>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Cette section est en cours de mise en place.
                    Le suivi des présences sera bientôt accessible.
                </p>
            </div>

            {{-- Info --}}
            <div
                class="rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3
                    flex items-start gap-2.5 text-left">
                <x-lucide-info class="w-4 h-4 text-sky-400 shrink-0 mt-0.5" />
                <p class="text-xs text-slate-400 leading-relaxed">
                    En attendant, vous pouvez consulter les listes d’apprenants
                    et les bulletins depuis le portail des classes.
                </p>
            </div>

        </div>
    </div>

</div>

