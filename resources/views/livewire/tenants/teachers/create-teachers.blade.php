<div class="flex flex-col gap-3 justify-center p-5">

    <div class="space-y-4 flex flex-col gap-2.5 w-4/5 justify-center mx-auto">
        @livewire('tenants.teachers.teachers-creation-monitor-component')
    </div>

    <div class="flex flex-col gap-2.5 w-4/5 justify-center mx-auto">
        <div class="space-y-6">
            <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                    <x-lucide-user class="w-5 h-5" />
                    <h3 class="">Vos informations personnelles</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Nom
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input wire:model.live='name' type="text" id="name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                placeholder="Votre nom">
                            @error('name')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="prenames">Prénoms
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model.live='prenames' id="prenames" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                placeholder="Vos prénoms ">
                            @error('prenames')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="email">Email
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model.live='email' id="email" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                placeholder="Votre mail....">
                            @error('email')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="contacts">Votre contact (unique)
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input wire:model.live='contacts' type="text" id="contacts" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                placeholder="01617777777">
                            @error('contacts')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="birth_date">Date de naissance
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input wire:model.live='birth_date' type="date" id="birth_date" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all">
                            @error('birth_date')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="gender">Genre
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model.live='gender' id="gender" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                <option value="">Sélectionnez le genre</option>
                                @foreach ($genders as $g)
                                    <option class="bg-slate-800 text-slate-300" value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="job_name">Fonction</label>
                        <div class="relative">
                            <input type="text" wire:model.live='job_name' id="job_name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                placeholder="Entrepreneur....">
                            @error('job_name')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-sky-500 rounded-2xl border border-sky-500">
                <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                    <x-lucide-map-pin-check class="w-5 h-5" />
                    <h3 class="">Adresse</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Pays
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model.live='country' id="country" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                <option value="">Sélectionnez le pays
                                </option>
                                @foreach ($countries as $ck => $ctn)
                                    <option class="bg-slate-800 text-slate-300" value="{{ $ctn }}">{{ $ctn }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300" for="department">Le département
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model.live='department' id="department" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                <option value="">Sélectionnez le département
                                </option>
                                @foreach ($departments as $dk => $dn)
                                    <option class="bg-slate-800 text-slate-300" value="{{ $dn }}">{{ $dn }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                    <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div wire:loading wire:target='department' wire:target='city'>
                        <div class="py-3 mt-3 flex justify-center items-center gap-x-3 text-gray-600">
                            <x-lucide-loader class="w-5 h-5 animate-spin" />
                            <h5>Chargement en cours ...</h5>
                        </div>
                    </div>
                    @if ($department)
                        <div data-animate='card' wire:target='department' wire:target='city' wire:loading.remove>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="city">La ville
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live='city' id="city" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez la ville</option>
                                    @foreach ($cities as $ck => $cn)
                                        <option class="bg-slate-800 text-slate-300" value="{{ $cn }}">{{ $cn }}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <button type="button" wire:loading.attr="disabled" wire:click="{{ $editingUuid ? 'updateTeacher' : 'addTeacher' }}"
                class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                <span class="flex items-center gap-1.5" wire:target='updateTeacher, addTeacher' wire:loading.remove>
                    <span>{{ $editingUuid ? 'Mettre à jour' : 'Ajouter' }}</span>
                    <x-lucide-user-plus class="w-4 h-4" />
                </span>
                <span wire:target='updateTeacher, addTeacher' wire:loading.flex class="items-center gap-1.5">
                    <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                    <span>En cours...</span>
                </span>
            </button>
        </div>

        <div class="mt-5 flex flex-col w-full gap-1.5 mb-40">
            @if (count($this->teachers))
                <section class="w-full">
                    <h4 class="p-2 rounded-lg border border-slate-800 bg-slate-950 overflow-hidden my-2">
                        Liste des données déjà ajoutées
                        <span>
                            Nombre:
                            {{ count($this->teachers) }}
                        </span>
                    </h4>

                    <div class="rounded-lg border border-slate-800 bg-slate-900 overflow-hidden">

                        <div class="overflow-x-auto">

                            <table class="w-full min-w-full text-xs">

                                <thead class="bg-slate-950 border-b border-slate-800">
                                    <tr>
                                        <th class="text-left p-2 font-medium text-slate-400">
                                            N°
                                        </th>
                                        <th class="text-left p-2 font-medium text-slate-400">
                                            Nom et Prenoms
                                        </th>

                                        <th class="text-left p-2 font-medium text-slate-400">
                                            Email
                                        </th>

                                        <th class="text-left p-2 font-medium text-slate-400">
                                            contacts
                                        </th>
                                        <th class="text-left p-2 font-medium text-slate-400">
                                            Date de naissance
                                        </th>

                                        <th class="text-center p-2 font-medium text-slate-400">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800">

                                    @foreach ($this->teachers as $teacher)
                                        <tr wire:key="{{ $teacher['uuid'] }}" class="hover:bg-slate-800/40 transition-all">
                                            <td class="p-2 truncate text-slate-300">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td class="p-2">

                                                <div class="flex items-center gap-4 min-w-0">

                                                    <div class="min-w-0">

                                                        <h3 class="font-medium truncate">
                                                            {{ $teacher['name'] }} {{ $teacher['prenames'] }} ({{ $teacher['gender'] }})
                                                        </h3>

                                                        <p class=text-slate-400 truncate">
                                                            {{ $teacher['job_name'] }}
                                                        </p>
                                                        <p class="text-xs">
                                                            {{ $teacher['city'] }} - {{ $teacher['department'] }} , {{ $teacher['country'] }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="p-2 truncate text-slate-300">
                                                {{ $teacher['email'] }}
                                            </td>

                                            <td class="p-2 truncate">
                                                {{ $teacher['contacts'] }}
                                            </td>
                                            <td class="p-2 truncate">
                                                {{ $teacher['birth_date'] }}
                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="p-2">

                                                <div class="flex items-center justify-end gap-2 truncate">
                                                    <button wire:key="edit-teacher-{{ $teacher['uuid'] }}" wire:click="editTeacher('{{ $teacher['uuid'] }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="editTeacher">
                                                            <x-lucide-pen class="w-4 h-4" />
                                                            Modifier
                                                        </span>
                                                        <span wire:loading.flex wire:target="editTeacher" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>

                                                    <button wire:key="rem-teacher-{{ $teacher['uuid'] }}" wire:click="deleteTeacher('{{ $teacher['uuid'] }}')" wire:loading.attr="disabled"
                                                        class="h-11 rounded-2xl flex items-center flex-1 justify-center cursor-pointer bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 ">
                                                        <span wire:loading.remove class="flex items-center gap-1.5" wire:target="deleteTeacher">
                                                            <x-lucide-trash-2 class="w-4 h-4" />
                                                            Supprimer
                                                        </span>
                                                        <span wire:loading.flex wire:target="deleteTeacher" class="items-center gap-1.5">
                                                            <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                                                            <span>En cours...</span>
                                                        </span>
                                                    </button>
                                                    <x-confirm-modal wire:key="confirm-teach-del-{{ $teacher['uuid'] }}" :show="$showTeacherRemoveModal" title="Retirer de la liste" confirm-text="Oui, Retirer" cancel-text="Annuler"
                                                        confirm-action="confirmDeleteTeacher" close-action="resetModal">
                                                        <p>
                                                            Cette action retirera cette donnée de la liste.
                                                        <p class="text-orange-500 font-semibold py-1.5">Action irreversible!</p>
                                                        </p>
                                                    </x-confirm-modal>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </section>
                <button type="button" wire:loading.attr="disabled" wire:click="{{ 'finish' }}" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-green-600 hover:bg-green-800">
                    <span class="flex items-center gap-1.5" wire:target='finish' wire:loading.remove>
                        <span>Terminer</span>
                        <x-lucide-send class="w-5 h-5" />
                    </span>
                    <span wire:target='finish' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>En cours...</span>
                    </span>
                </button>
            @endif
        </div>
    </div>
</div>

