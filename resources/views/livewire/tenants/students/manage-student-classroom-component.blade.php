<div>
    {{-- Carte apprenant --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <a wire:navigate href="{{ $this->student->toProfilRoute() }}"
            class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center group hover:underline hover:underline-offset-4 hover:text-sky-600">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-16 h-16 rounded-full bg-slate-800 border-4 border-white overflow-hidden group-hover:text-sky-700 group-hover:border-sky-700">
                    <img src="{{ $this->student->profil_photo_url }}" alt="Photo de profil"
                        class="w-full h-full object-cover group-hover:text-sky-700" />
                </div>
                <div class="text-slate-500">
                    <span class="uppercase">apprenant : </span>
                    <span>{{ $this->student->getFullName() }}</span>
                </div>
            </div>
        </a>
        <div class="mt-3 flex flex-col gap-2">
            @if ($this->currentClasse)
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 uppercase font-mono">
                    Classe actuelle : {{ $this->currentClasse->name }}
                </span>
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1 text-sm font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-300 uppercase font-mono gap-x-2">
                    <span>Matricule : {{ $this->student->matricule }}</span>
                    <span>||</span>
                    <span>EducMaster : {{ $this->student->educMaster }}</span>
                </span>
            @else
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 text-sm font-medium text-rose-600 dark:bg-rose-700/25 dark:text-rose-300">
                    Aucune classe assignée
                </span>
            @endif
        </div>
    </div>
    @if ($this->currentClasse)
        <div class="flex justify-end my-4">
            <button type="button" wire:click="removeStudentFromCurrent" wire:loading.attr="disabled"
                wire:target="removeStudentFromCurrent"
                class="inline-flex items-center gap-2 rounded-md bg-red-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-red-700 disabled:opacity-60">
                <span wire:loading.remove wire:target="removeStudentFromCurrent"
                    class="flex justify-center items-center">
                    <span class="flex items-center gap-3">
                        <x-lucide-user-x class="w-4 h-4 " />
                        <span>Retirer la classe actuelle</span>
                    </span>
                </span>
                <span wire:loading wire:target="removeStudentFromCurrent" class="flex items-center gap-2">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                        Traitement en cours...
                    </span>
                </span>
            </button>
        </div>
    @endif
    @if ($errors->any())
        <h5
            class="my-11 mx-auto flex justify-center text-lg bg-red-500/10 text-red-400 ls-1 font-semibold animate-pulse py-4 rounded-2xl">
            Le formulaire est
            incorrect</h5>
    @endif
    <div
        class="relative rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div wire:loading wire:target="migrateStudent"
            class="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-white/60 dark:bg-gray-800/60">
            <svg class="h-6 w-6 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ $this->currentClasse ? 'Migrer vers' : 'Assigner à' }} une classe
        </label>

        <x-select searchable wire:model="selectedClasseId" :options="$this->availableClasses" option-label="name" option-value="id"
            placeholder="Rechercher une classe...">
            @foreach ($this->availableClasses as $classe)
                <x-select.option :value="$classe->id" :label="$classe->name" :disabled="$this->currentClasse && $classe->id === $this->currentClasse->id" />
            @endforeach
        </x-select>

        <div class="mt-4 flex justify-end">
            <button type="button" wire:click="confirmMigration" wire:loading.attr="disabled"
                wire:target="confirmMigration"
                class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:opacity-60">
                <span wire:loading.remove wire:target="confirmMigration">
                    {{ $this->currentClasse ? "Migrer l'apprenant" : 'Assigner la classe' }}
                </span>
                <span wire:loading wire:target="confirmMigration" class="flex items-center gap-2">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                        Traitement...
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>

