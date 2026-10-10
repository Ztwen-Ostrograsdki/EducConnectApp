<div class="space-y-6 text-slate-800 dark:text-slate-100">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[.18em] text-cyan-700 dark:text-cyan-300">Organisation pédagogique</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight">Emplois du temps</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Un emploi du temps par classe et par année scolaire.</p>
        </div>
        <button type="button" wire:click="openCreatePlan" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
            <x-lucide-plus class="h-4 w-4" /> Nouvel emploi du temps
        </button>
    </div>

    <div class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:grid-cols-[minmax(220px,1fr)_minmax(220px,2fr)]">
        <div>
            <label for="school_year_id" class="mb-1.5 block text-sm font-medium">Année scolaire</label>
            <select id="school_year_id" wire:model.live="school_year_id" class="w-full rounded-xl border-slate-300 bg-white text-sm focus:border-cyan-600 focus:ring-cyan-600 dark:border-slate-700 dark:bg-slate-950">
                <option value="">Choisir une année</option>
                @foreach ($this->schoolYears as $year)
                    <option value="{{ $year->id }}">{{ $year->min_year }}–{{ $year->max_year }}{{ $year->is_closed ? ' (clôturée)' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="search" class="mb-1.5 block text-sm font-medium">Rechercher une classe</label>
            <div class="relative">
                <x-lucide-search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input id="search" type="search" wire:model.live.debounce.300ms="search" placeholder="Nom de la classe…" class="w-full rounded-xl border-slate-300 bg-white py-2 pl-9 text-sm focus:border-cyan-600 focus:ring-cyan-600 dark:border-slate-700 dark:bg-slate-950" />
            </div>
        </div>
    </div>

    @if ($this->plans->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 dark:bg-cyan-950/50 dark:text-cyan-300"><x-lucide-calendar-clock class="h-6 w-6" /></div>
            <h2 class="mt-4 font-semibold">Aucun emploi du temps</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Crée le premier emploi du temps pour cette année scolaire.</p>
            <button wire:click="openCreatePlan" class="mt-4 rounded-xl bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-600">Créer un emploi du temps</button>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->plans as $plan)
                <article wire:key="plan-{{ $plan->id }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-cyan-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-cyan-800">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-bold">{{ $plan->classe?->name ?? 'Classe supprimée' }}</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $plan->title ?: 'Emploi du temps de la classe' }}</p>
                        </div>
                        @php($statusClasses = ['draft' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-950/40 dark:text-yellow-300', 'published' => 'bg-cyan-50 text-cyan-800 ring-cyan-600/20 dark:bg-cyan-950/40 dark:text-cyan-300', 'archived' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-300'])
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClasses[$plan->status] ?? $statusClasses['draft'] }}">
                            {{ ['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'][$plan->status] ?? $plan->status }}
                        </span>
                    </div>
                    <div class="mt-5 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                        <x-lucide-calendar-days class="h-4 w-4" /> {{ $plan->schoolYear?->min_year }}–{{ $plan->schoolYear?->max_year }}
                        <span class="mx-1 text-slate-300 dark:text-slate-700">•</span>
                        <x-lucide-clock class="h-4 w-4" /> {{ $plan->slots_count }} créneau(x)
                    </div>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <button wire:click="openPlan({{ $plan->id }})" class="inline-flex items-center gap-1.5 rounded-lg bg-cyan-50 px-3 py-2 text-sm font-semibold text-cyan-800 hover:bg-cyan-100 dark:bg-cyan-950/50 dark:text-cyan-200 dark:hover:bg-cyan-950"><x-lucide-eye class="h-4 w-4" /> Ouvrir</button>
                        @if ($plan->status !== 'archived')
                            <button wire:click="editPlan({{ $plan->id }})" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"><x-lucide-pencil class="h-4 w-4" /> Modifier</button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    @if ($this->currentPlan)
        <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm text-cyan-700 dark:text-cyan-300">{{ $this->currentPlan->schoolYear?->min_year }}–{{ $this->currentPlan->schoolYear?->max_year }}</p>
                    <h2 class="mt-1 text-xl font-bold">{{ $this->currentPlan->classe?->name }} — {{ $this->currentPlan->title ?: 'Emploi du temps' }}</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Les enseignants sont résolus depuis l’affectation active de chaque matière.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if ($this->currentPlan->status !== 'archived')
                        <button wire:click="openCreateSlot" class="inline-flex items-center gap-2 rounded-xl bg-cyan-700 px-3.5 py-2 text-sm font-semibold text-white hover:bg-cyan-600"><x-lucide-plus class="h-4 w-4" /> Ajouter un créneau</button>
                        @if ($this->currentPlan->status !== 'published')
                            <button wire:click="publishPlan" wire:confirm="Publier cet emploi du temps ?" class="rounded-xl border border-cyan-700 px-3.5 py-2 text-sm font-semibold text-cyan-800 hover:bg-cyan-50 dark:text-cyan-300 dark:hover:bg-cyan-950/40">Publier</button>
                        @endif
                        <button wire:click="archivePlan" wire:confirm="Archiver cet emploi du temps ? Il ne sera plus modifiable." class="rounded-xl border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Archiver</button>
                    @endif
                </div>
            </div>

            @if ($this->currentPlan->slots->isEmpty())
                <p class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500 dark:bg-slate-950 dark:text-slate-400">Aucun créneau enregistré pour le moment.</p>
            @else
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400"><tr><th class="px-4 py-3">Jour</th><th class="px-4 py-3">Horaire</th><th class="px-4 py-3">Matière</th><th class="px-4 py-3">Enseignant</th><th class="px-4 py-3">Actions</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($this->currentPlan->slots as $slot)
                                <tr wire:key="slot-{{ $slot->id }}" class="align-top hover:bg-slate-50/70 dark:hover:bg-slate-950/50">
                                    <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $slot->day_name }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 tabular-nums">{{ substr((string) $slot->starts_at, 0, 5) }}–{{ substr((string) $slot->ends_at, 0, 5) }}</td>
                                    <td class="px-4 py-3">{{ $slot->subject?->name ?? 'Affectation indisponible' }} @if($slot->label)<p class="mt-1 text-xs text-slate-500">{{ $slot->label }}</p>@endif</td>
                                    <td class="px-4 py-3">{{ trim(($slot->teacher?->name ?? '') . ' ' . ($slot->teacher?->prenoms ?? '')) ?: 'Enseignant non disponible' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        @if ($this->currentPlan->status !== 'archived')
                                            <button wire:click="editSlot({{ $slot->id }})" aria-label="Modifier le créneau" class="rounded-lg p-2 text-slate-500 hover:bg-cyan-50 hover:text-cyan-800 dark:hover:bg-cyan-950/50"><x-lucide-pencil class="h-4 w-4" /></button>
                                            <button wire:click="deleteSlot({{ $slot->id }})" wire:confirm="Supprimer ce créneau ?" aria-label="Supprimer le créneau" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:hover:bg-slate-800"><x-lucide-trash-2 class="h-4 w-4" /></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endif

    @if ($showPlanForm)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 p-0 backdrop-blur-sm sm:items-center sm:p-4" role="dialog" aria-modal="true" aria-labelledby="plan-form-title" wire:keydown.escape="$set('showPlanForm', false)">
            <div class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl dark:bg-slate-900 sm:rounded-2xl sm:p-6">
                <div class="flex items-start justify-between gap-4"><div><h2 id="plan-form-title" class="text-xl font-bold">{{ $timePlanId ? 'Modifier l’emploi du temps' : 'Créer un emploi du temps' }}</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Une seule fiche par classe et par année.</p></div><button wire:click="$set('showPlanForm', false)" class="rounded-lg p-2 hover:bg-slate-100 dark:hover:bg-slate-800"><x-lucide-x class="h-5 w-5" /></button></div>
                <form wire:submit="savePlan" class="mt-5 space-y-4">
                    <div><label class="mb-1.5 block text-sm font-medium">Année scolaire</label><select wire:model="school_year_id" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"><option value="">Choisir…</option>@foreach($this->schoolYears as $year)<option value="{{ $year->id }}">{{ $year->min_year }}–{{ $year->max_year }}</option>@endforeach</select>@error('school_year_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-1.5 block text-sm font-medium">Classe</label><select wire:model="classe_id" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"><option value="">Choisir une classe…</option>@foreach($this->classes as $classe)<option value="{{ $classe->id }}">{{ $classe->name }}</option>@endforeach</select>@error('classe_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-1.5 block text-sm font-medium">Titre (facultatif)</label><input wire:model="title" type="text" maxlength="150" placeholder="Ex. Emploi du temps général" class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950" />@error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-1.5 block text-sm font-medium">Notes (facultatif)</label><textarea wire:model="notes" rows="3" class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"></textarea>@error('notes')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 dark:border-slate-800"><button type="button" wire:click="$set('showPlanForm', false)" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-slate-700">Annuler</button><button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-600 disabled:opacity-60"><span wire:loading.remove wire:target="savePlan">Enregistrer</span><span wire:loading wire:target="savePlan">Enregistrement…</span></button></div>
                </form>
            </div>
        </div>
    @endif

    @if ($showSlotForm && $this->currentPlan)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 p-0 backdrop-blur-sm sm:items-center sm:p-4" role="dialog" aria-modal="true" aria-labelledby="slot-form-title" wire:keydown.escape="$set('showSlotForm', false)">
            <div class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl dark:bg-slate-900 sm:rounded-2xl sm:p-6">
                <div class="flex items-start justify-between gap-4"><div><h2 id="slot-form-title" class="text-xl font-bold">{{ $slotId ? 'Modifier le créneau' : 'Ajouter un créneau' }}</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $this->currentPlan->classe?->name }} · {{ $this->currentPlan->schoolYear?->min_year }}–{{ $this->currentPlan->schoolYear?->max_year }}</p></div><button wire:click="$set('showSlotForm', false)" class="rounded-lg p-2 hover:bg-slate-100 dark:hover:bg-slate-800"><x-lucide-x class="h-5 w-5" /></button></div>
                <form wire:submit="saveSlot" class="mt-5 space-y-4">
                    <div><label class="mb-1.5 block text-sm font-medium">Matière et enseignant affecté</label><select wire:model="assignment_id" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"><option value="">Choisir une affectation…</option>@foreach($this->assignments as $assignment)<option value="{{ $assignment->id }}">{{ $assignment->subject->name }} — {{ trim(($assignment->teacher->name ?? '') . ' ' . ($assignment->teacher->prenoms ?? '')) }}</option>@endforeach</select>@error('assignment_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror@if($this->assignments->isEmpty())<p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">Aucune affectation active matière-classe pour cette année scolaire.</p>@endif</div>
                    <div><label class="mb-1.5 block text-sm font-medium">Jour</label><select wire:model="day_of_week" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"><option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option><option value="4">Jeudi</option><option value="5">Vendredi</option><option value="6">Samedi</option><option value="7">Dimanche</option></select>@error('day_of_week')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div class="grid grid-cols-2 gap-3"><div><label class="mb-1.5 block text-sm font-medium">Heure de début</label><input wire:model="starts_at" type="time" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950" />@error('starts_at')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div><div><label class="mb-1.5 block text-sm font-medium">Heure de fin</label><input wire:model="ends_at" type="time" required class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950" />@error('ends_at')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div></div>
                    <div><label class="mb-1.5 block text-sm font-medium">Libellé facultatif</label><input wire:model="slot_label" maxlength="150" placeholder="Ex. Travaux pratiques" class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950" />@error('slot_label')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-1.5 block text-sm font-medium">Notes facultatives</label><textarea wire:model="slot_notes" rows="2" class="w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950"></textarea>@error('slot_notes')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4 dark:border-slate-800"><button type="button" wire:click="$set('showSlotForm', false)" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-slate-700">Annuler</button><button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-600 disabled:opacity-60"><span wire:loading.remove wire:target="saveSlot">Enregistrer le créneau</span><span wire:loading wire:target="saveSlot">Enregistrement…</span></button></div>
                </form>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-200" role="alert">
            <p class="font-semibold">Certaines informations doivent être corrigées.</p>
            <ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
</div>
