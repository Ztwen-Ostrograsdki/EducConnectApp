<div class="bg-slate-950 min-h-screen flex items-center justify-center p-4 md:p-8">

    @if ($done)
        <section data-animate='card' class="w-full max-w-5xl glass rounded-2xl p-8 md:p-12 shadow-glow">
            <div id="alert-additional-content-3" class="p-4 mb-4 text-sm bg-green-500 text-green-900" role="alert">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 shrink-0 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <h3 class="font-medium">This is a success alert</h3>
                    </div>
                    <button type="button" data-dismiss-target="#alert-additional-content-3" aria-label="Close"
                        class="ms-auto -mx-1.5 -my-1.5 bg-success-soft text-fg-success-strong rounded focus:ring-2 focus:ring-success-medium p-1.5 hover:bg-success-medium inline-flex items-center justify-center h-8 w-8 shrink-0">
                        <span class="sr-only">Close</span>
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2 mb-4">
                    More info about this info alert goes here. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.
                </div>
                <button type="button"
                    class="inline-flex items-center text-white bg-success box-border border border-transparent hover:bg-success-strong focus:ring-4 focus:ring-success-medium shadow-xs font-medium leading-5 rounded-base text-xs px-3 py-1.5 focus:outline-none">
                    <svg class="w-3.5 h-3.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    View more
                </button>
            </div>
        </section>
    @else
        <section class="w-full max-w-5xl glass rounded-2xl p-8 md:p-12 shadow-glow">

            <div class="mb-10 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-2 gradient-text">Créez votre espace</h2>
                <p class="text-gray-400">Et prenez le controle, soyez pro</p>
            </div>

            @if ($errors->any())

                <div class="flex p-4 mb-4 text-sm border border-red-200 bg-red-200 text-red-900 rounded-2xl" role="alert">
                    <svg class="w-4 h-4 me-2 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="sr-only">Danger</span>
                    <div>
                        <h4 class="font-medium">FORMULAIRE INCORRECT : Vérifiez que tous les champs sont bien rensignés:</h4>
                        <ul class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($errors->all() as $er)
                                <li class="flex gap-1 border rounded-xl p-2 bg-red-300 items-center">
                                    <x-lucide-circle-x class="w-3 h-3" />
                                    <span>{{ $er }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent='submit' class="space-y-6">
                <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-slate-700 rounded-2xl border border-slate-700">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-user class="w-5 h-5" />
                        <h3 class="">Vos informations personnelles</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Nom </label>
                            <div class="relative">
                                <input type="text" id="name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Votre nom">
                                @error('name')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="prenames">Prénoms</label>
                            <div class="relative">
                                <input type="text" id="prenames" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Vos prénoms ">
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="contacts">Votre unique contacts </label>
                            <div class="relative">
                                <input type="text" id="contacts" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="01617777777">
                                @error('contacts')
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
                                <input type="text" id="job_name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all" placeholder="Entrepreneur....">
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

                <div class="flex flex-col gap-y-2 w-full p-3 shadow-md shadow-amber-500 rounded-2xl border border-amber-500">
                    <div class="flex justify-start gap-x-2 border-b border-gray-700 py-2 text-gray-500 mb-2.5">
                        <x-lucide-school class="w-5 h-5" />
                        <h3 class="">Les informations de l'école</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="school_name">Nom complet de l'école(diminutif) </label>
                            <div class="relative">
                                <input type="text" id="school_name" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="Nom complet de l'école (CSGD)">
                                @error('school_name')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="school_devise">Dévise de l'école</label>
                            <div class="relative">
                                <input id="school_devise" type="text" class="w-full bg-gray-900/50 border border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all"
                                    placeholder="Succès - Discipline - Travail ">
                                @error('school_devise')
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="enseignement_type">Type d'énseignement </label>
                            <div class="relative">
                                <select name="enseignement_type" id="enseignement_type" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le type d'enseignement</option>
                                    @foreach ($enseignement_types as $et)
                                        <option class="bg-slate-800 text-slate-300" value="{{ $et }}">{{ $et }}</option>
                                    @endforeach
                                </select>
                                @error('enseignement_type')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="school_type">Type d'école </label>
                            <div class="relative">
                                <select name="school_type" id="school_type" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le type d'école</option>
                                    @foreach ($school_types as $st)
                                        <option class="bg-slate-800 text-slate-300" value="{{ $st }}">{{ $st }}</option>
                                    @endforeach
                                </select>
                                @error('school_type')
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="periode_type">Type de période </label>
                            <div class="relative">
                                <select name="periode_type" id="periode_type" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le type de période</option>
                                    @foreach ($periode_types as $pt)
                                        <option class="bg-slate-800 text-slate-300" value="{{ $pt }}">{{ $pt }}</option>
                                    @endforeach
                                </select>
                                @error('periode_type')
                                    <span class="flex items-center p-2 text-sm text-red-400 gap-x-2">
                                        <x-lucide-octagon-alert class="w-4 h-4 text-red-500" />
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="devoirs_type">Les devoirs par {{ $periode_type ? $periode_type : 'période' }} </label>
                            <div class="relative">
                                <select name="devoirs_type" id="devoirs_type" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le type</option>
                                    @foreach ($devoirs_types as $dt)
                                        <option class="bg-slate-800 text-slate-300" value="{{ $dt }}">{{ $dt }}</option>
                                    @endforeach
                                </select>
                                @error('devoirs_type')
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
                        <h3 class="">Localisation de l'école</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="name">Pays </label>
                            <div class="relative">
                                <select wire:model.live='country' name="country" id="country" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le pays</option>
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
                            <label class="block text-sm font-medium mb-2 text-gray-300" for="department">Le département </label>
                            <div class="relative">
                                <select wire:model.live='department' name="department" id="department"
                                    class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
                                    <option value="">Sélectionnez le département</option>
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
                            <div wire:target='department' wire:target='city' wire:loading.remove>
                                <label class="block text-sm font-medium mb-2 text-gray-300" for="city">La ville </label>
                                <div class="relative">
                                    <select wire:model='city' name="city" id="city" class="w-full border bg-gray-900/50 border-gray-800 rounded-xl py-3 px-4 focus:outline-none focus:border-primary-500 transition-all ">
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

                <button type="submit" wire:loading.attr="disabled" class="p-3 rounded-2xl w-full flex items-center justify-center cursor-pointer bg-sky-600 hover:bg-sky-800">
                    <span class="flex items-center gap-1.5" wire:target='submit' wire:loading.remove>
                        <span>Soumettre ma demande</span>
                        <x-lucide-send class="w-5 h-5" />
                    </span>
                    <span wire:target='submit' wire:loading.flex class="items-center gap-1.5">
                        <x-lucide-refresh-ccw class="w-5 h-5 animate-spin" />
                        <span>En cours...</span>
                    </span>
                </button>
            </form>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="glass p-6 rounded-xl hover:bg-gray-900/20 transition-all">
                    <i class="fa-solid fa-phone text-3xl mb-4 text-primary-500"></i>
                    <h4 class="font-semibold mb-2">Call Us</h4>
                    <p class="text-gray-400 text-sm">+1 (555) 123-4567</p>
                </div>
                <div class="glass p-6 rounded-xl hover:bg-gray-900/20 transition-all">
                    <i class="fa-solid fa-envelope text-3xl mb-4 text-secondary-500"></i>
                    <h4 class="font-semibold mb-2">Email Us</h4>
                    <p class="text-gray-400 text-sm">support@company.com</p>
                </div>
                <div class="glass p-6 rounded-xl hover:bg-gray-900/20 transition-all">
                    <i class="fa-solid fa-location-dot text-3xl mb-4 text-accent-500"></i>
                    <h4 class="font-semibold mb-2">Visit Us</h4>
                    <p class="text-gray-400 text-sm">New York, USA</p>
                </div>
            </div>
        </section>
    @endif

</div>

