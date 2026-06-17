<div class="flex flex-col gap-1 p-5 w-full justify-center mx-auto">
    <section class="mb-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">
            <div class="relative h-32 sm:h-44 w-full overflow-hidden">
                <img src="{{ $student->profil_photo_url }}" alt="Photo de couverture" class="w-full h-full object-cover object-top scale-110" />
                <div class="absolute inset-0 bg-linear-to-br from-indigo-950/70 via-slate-900/50 to-slate-950/80"></div>
                <div class="absolute -top-10 -left-10 w-64 h-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
                <div class="absolute top-0 right-1/3 w-48 h-48 rounded-full bg-violet-600/20 blur-3xl"></div>
                <div class="absolute -bottom-8 right-10 w-56 h-56 rounded-full bg-sky-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 right-0 h-24 bg-linear-to-t from-slate-900 to-transparent"></div>
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="px-6 py-3 rounded-2xl backdrop-blur-md">
                        <h2 class="text-2xl sm:text-4xl font-normal text-gray-500/80 tracking-wider">
                            Mise à jour des informations de l'apprenant
                        </h2>
                    </div>
                </div>
            </div>
            <div class="px-5 sm:px-8 pb-6 sm:pb-8">

                <div class="flex flex-col xl:flex-row gap-8">
                    <div class="flex flex-col items-center xl:items-start relative z-20">
                        <div class="relative shrink-0">
                            <div class="absolute -inset-1 rounded-3xl bg-gradient-to-br from-indigo-500 via-violet-500 to-sky-500 opacity-70 blur-sm"></div>
                            <div class="relative w-32 h-32 rounded-3xl bg-slate-800 ring-4 ring-slate-900 shrink-0 overflow-hidden">
                                <img src="{{ $student->profil_photo_url }}" alt="Photo de profil" class="w-full h-full object-cover" />
                            </div>
                            <span class="absolute -bottom-1.5 -right-1.5 w-5 h-5 rounded-full bg-emerald-500 ring-2 ring-slate-900 block"></span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3 justify-center xl:justify-start">
                            <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs">
                                Terminale F2-1
                            </span>
                        </div>
                    </div>
                    {{-- INFOS --}}
                    <div class="flex-1 min-w-0 pt-4">

                        <div class="flex flex-col 2xl:flex-row 2xl:items-start 2xl:justify-between gap-6">

                            <div class="min-w-0">

                                <h1 class="text-3xl sm:text-4xl font-bold break-words">
                                    <span>
                                        {{ $student->prenames }}
                                    </span>
                                    <span>
                                        {{ $student->name }}
                                    </span>
                                </h1>

                                <p class="mt-2 text-slate-400 inline-flex flex-col items-center gap-y-1">
                                    <span class="">
                                        Matricule : {{ $student->matricule }}
                                    </span>
                                    <span class="text-slate-600">
                                        EducMaster : {{ $student->educMaster }}
                                    </span>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <div class="flex-col justify-center w-full mx-auto">
        <div>
            <div class="space-y-6">
                <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-user class="w-5 h-5" />
                        <h3 class="">Informations personnelles de l'apprenant</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Nom
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input wire:model.live='name' type="text" id="name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="Nom de l'apprenant">
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
                                    placeholder="Prenoms de l'apprenant ">
                                @error('prenames')
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="educMaster">EducMaster
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" wire:model.live='educMaster' id="educMaster" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="N° educMaster de l'apprenant">
                                @error('educMaster')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="contacts">Contact d'un parent légitime (unique)
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
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="email">Email
                                <span class="text-sky-500">facultative</span>
                            </label>
                            <div class="relative">
                                <input type="text" wire:model.live='email' id="email" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="Email personnel de l'apprenant">
                                @error('email')
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="birth_place">Lieu de naissance</label>
                            <div class="relative">
                                <input type="text" wire:model.live='birth_place' id="birth_place" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="Lieu de naissance....">
                                @error('birth_place')
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
                    </div>
                </div>

                <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-sky-500 rounded-2xl border border-sky-500">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-map-pin-check class="w-5 h-5" />
                        <h3 class="">Adresse actuelle de l'apprenant</h3>
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

                <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-user class="w-5 h-5" />
                        <h3 class="">informations sur géniteurs (si vivant)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="father_full_name">Nom et Prenoms du père
                                <span class="text-sky-500">(facultative)</span>
                            </label>
                            <div class="relative">
                                <input wire:model.live='father_full_name' type="text" id="father_full_name"
                                    class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Nom et prénoms du père de l'apprenant">
                                @error('father_full_name')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="mother_full_name">Nom et Prenoms de la mère
                                <span class="text-sky-500">(facultative)</span>
                            </label>
                            <div class="relative">
                                <input wire:model.live='mother_full_name' type="text" id="mother_full_name"
                                    class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Nom et prénoms de la mère de l'apprenant">
                                @error('mother_full_name')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
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
    </div>
</div>

