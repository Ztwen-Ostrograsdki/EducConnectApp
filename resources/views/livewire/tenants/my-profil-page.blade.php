@php
    $v = function ($value, string $fallback = 'Non renseigné') {
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return $fallback;
        }
        return $value;
    };
    $empty = function ($value) {
        return $value === null || $value === '' || (is_string($value) && trim($value) === '');
    };

    $genderLabel = match (strtoupper((string) ($user->gender ?? ''))) {
        'M', 'MASCULIN' => 'Masculin',
        'F', 'FEMININ', 'FÉMININ' => 'Féminin',
        default => $v($user->gender),
    };

    $birthFormatted = $user->birth_date
        ? (function_exists('__formatDate')
            ? ucwords(__formatDate($user->birth_date))
            : $user->birth_date?->format('d/m/Y'))
        : null;

    $age =
        $user->birth_date && function_exists('getAge')
            ? getAge($user->birth_date)
            : ($user->birth_date
                ? $user->birth_date->age
                : null);

    $createdFormatted = function_exists('__formatDateTime')
        ? __formatDateTime($user->created_at)
        : $user->created_at?->format('d/m/Y H:i');

    $idExpired = $user->ID_card_expired_date
        ? (function_exists('__formatDate')
            ? __formatDate($user->ID_card_expired_date)
            : (string) $user->ID_card_expired_date)
        : null;

    $firstJob = $user->first_take_job_date
        ? (function_exists('__formatDate')
            ? __formatDate($user->first_take_job_date)
            : (string) $user->first_take_job_date)
        : null;

    $field = function (string $label, $value, string $iconHtml, bool $isEmpty = false, bool $mono = false) {
        $valClass = $isEmpty
            ? 'text-slate-500 italic text-sm font-medium'
            : 'text-white text-sm font-medium' . ($mono ? ' font-mono' : '');
        return compact('label', 'value', 'iconHtml', 'valClass');
    };
@endphp

<div class="mx-auto w-full px-4 sm:px-6 pb-28 space-y-5">
    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-3xl border border-white/[0.07] bg-[#0a0f1a]">
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(99,102,241,0.18),_transparent_55%),radial-gradient(ellipse_at_bottom_left,_rgba(6,182,212,0.12),_transparent_50%)]">
        </div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-indigo-400/40 to-transparent">
        </div>

        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center gap-6 md:gap-8">
                <div class="relative mx-auto md:mx-0 shrink-0 group">
                    <div
                        class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 opacity-70 blur-[2px] group-hover:opacity-100 transition-opacity">
                    </div>
                    <div
                        class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden border-2 border-[#0a0f1a] bg-[#0a0f1a]">
                        <img src="{{ auth()->guard('tenant')->user()->profil_photo_url }}" alt="Photo de profil"
                            class="w-full h-full object-cover">
                    </div>
                    <a href="{{ route('tenant.update.profil.photo') }}" title="Modifier la photo"
                        class="absolute -bottom-2 -right-2 h-9 w-9 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-900/40 transition-transform hover:scale-105 active:scale-95">
                        <x-lucide-camera class="w-4 h-4" />
                    </a>
                </div>

                <div class="flex-1 min-w-0 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                        <span @class([
                            'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold border',
                            'bg-emerald-500/10 text-emerald-300 border-emerald-500/25' => !$user->blocked,
                            'bg-rose-500/10 text-rose-300 border-rose-500/25' => $user->blocked,
                        ])>
                            <span @class([
                                'h-1.5 w-1.5 rounded-full',
                                'bg-emerald-400' => !$user->blocked,
                                'bg-rose-400' => $user->blocked,
                            ])></span>
                            {{ $user->blocked ? 'Compte bloqué' : 'Compte actif' }}
                        </span>
                        @foreach ($user->roles as $role)
                            <span
                                class="px-2.5 py-1 rounded-lg text-[11px] font-medium bg-white/5 text-slate-300 border border-white/10">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight truncate">
                        {{ $user->getFullName(true) }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $v($user->job_name, 'Profession non renseignée') }}
                        <span class="text-slate-600 mx-1.5">·</span>
                        <span class="font-mono text-slate-500 text-xs">{{ $user->email }}</span>
                    </p>

                    <div class="mt-4 flex flex-wrap items-center justify-center md:justify-start gap-2">
                        <a wire:navigate href="{{ route('tenant.update.user.data') }}"
                            class="h-9 px-4 rounded-xl text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-500/30 hover:bg-indigo-500/30 transition-all inline-flex items-center gap-1.5">
                            <x-lucide-user-pen class="w-3.5 h-3.5" />
                            Éditer le profil
                        </a>
                        <a href="{{ route('tenant.update.password') }}"
                            class="h-9 px-3.5 rounded-xl text-xs font-medium bg-white/5 text-slate-300 border border-white/10 hover:bg-white/10 transition-all inline-flex items-center gap-1.5">
                            <x-lucide-key-round class="w-3.5 h-3.5" />
                            Mot de passe
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-7 grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <x-lucide-phone class="w-3.5 h-3.5 text-cyan-400" />
                        <span class="text-[10px] uppercase tracking-wider font-medium">Téléphone</span>
                    </div>
                    <p @class([
                        'text-sm font-medium truncate',
                        'text-white' => !$empty($user->contacts),
                        'text-slate-500 italic' => $empty($user->contacts),
                    ])>
                        {{ $empty($user->contacts) ? 'Non renseigné' : '+229 ' . $user->contacts }}
                    </p>
                </div>
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <x-lucide-map-pin class="w-3.5 h-3.5 text-emerald-400" />
                        <span class="text-[10px] uppercase tracking-wider font-medium">Localisation</span>
                    </div>
                    <p @class([
                        'text-sm font-medium truncate',
                        'text-white' => !$empty($user->city) || !$empty($user->country),
                        'text-slate-500 italic' => $empty($user->city) && $empty($user->country),
                    ])>
                        @if (!$empty($user->city) || !$empty($user->country))
                            {{ collect([$user->city, $user->country])->filter()->implode(', ') }}
                        @else
                            Non renseigné
                        @endif
                    </p>
                </div>
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <x-lucide-cake class="w-3.5 h-3.5 text-fuchsia-400" />
                        <span class="text-[10px] uppercase tracking-wider font-medium">Naissance</span>
                    </div>
                    <p @class([
                        'text-sm font-medium truncate',
                        'text-white' => $birthFormatted,
                        'text-slate-500 italic' => !$birthFormatted,
                    ])>
                        {{ $v($birthFormatted) }}@if ($age)
                            <span class="text-slate-500 font-normal"> · {{ $age }} ans</span>
                        @endif
                    </p>
                </div>
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <x-lucide-hash class="w-3.5 h-3.5 text-amber-400" />
                        <span class="text-[10px] uppercase tracking-wider font-medium">Matricule</span>
                    </div>
                    <p @class([
                        'text-sm font-medium font-mono truncate',
                        'text-white' => !$empty($user->matricule_number),
                        'text-slate-500 italic font-sans' => $empty($user->matricule_number),
                    ])>
                        {{ $v($user->matricule_number) }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Gauche --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- État civil --}}
            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-sky-500/15 border border-sky-500/25 flex items-center justify-center text-sky-400">
                        <x-lucide-user class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">État civil</h2>
                        <p class="text-[11px] text-slate-500">Identité & situation personnelle</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
                            <x-lucide-user-round class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Nom complet</p>
                            <p class="text-sm font-medium text-white truncate">{{ $user->getFullName(true) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                            <x-lucide-users class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Genre</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->gender),
                                'text-slate-500 italic' => $empty($user->gender),
                            ])>{{ $genderLabel }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400">
                            <x-lucide-heart class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Situation matrimoniale
                            </p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->marital_status),
                                'text-slate-500 italic' => $empty($user->marital_status),
                            ])>{{ $v($user->marital_status) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-fuchsia-500/10 border border-fuchsia-500/20 flex items-center justify-center text-fuchsia-400">
                            <x-lucide-cake class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Date de naissance</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => $birthFormatted,
                                'text-slate-500 italic' => !$birthFormatted,
                            ])>{{ $v($birthFormatted) }}@if ($age)
                                    <span class="text-slate-500 font-normal">({{ $age }} ans)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400">
                            <x-lucide-map-pinned class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Lieu de naissance</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->birth_place),
                                'text-slate-500 italic' => $empty($user->birth_place),
                            ])>{{ $v($user->birth_place) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <x-lucide-briefcase class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Profession</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->job_name),
                                'text-slate-500 italic' => $empty($user->job_name),
                            ])>{{ $v($user->job_name) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Coordonnées --}}
            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                        <x-lucide-map-pin class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Coordonnées</h2>
                        <p class="text-[11px] text-slate-500">Contact et localisation</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <x-lucide-mail class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Email</p>
                            <p class="text-sm font-medium text-white truncate">{{ $v($user->email) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                            <x-lucide-phone class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Téléphone</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->contacts),
                                'text-slate-500 italic' => $empty($user->contacts),
                            ])>{{ $v($user->contacts) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3 sm:col-span-2">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400">
                            <x-lucide-home class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Adresse</p>
                            <p @class([
                                'text-sm font-medium',
                                'text-white' => !$empty($user->adresse),
                                'text-slate-500 italic' => $empty($user->adresse),
                            ])>{{ $v($user->adresse) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <x-lucide-flag class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Pays</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->country),
                                'text-slate-500 italic' => $empty($user->country),
                            ])>{{ $v($user->country) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
                            <x-lucide-building-2 class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Ville</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->city),
                                'text-slate-500 italic' => $empty($user->city),
                            ])>{{ $v($user->city) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3 sm:col-span-2">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                            <x-lucide-landmark class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Département</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->department),
                                'text-slate-500 italic' => $empty($user->department),
                            ])>{{ $v($user->department) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Carrière --}}
            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-violet-500/15 border border-violet-500/25 flex items-center justify-center text-violet-400">
                        <x-lucide-briefcase class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Carrière & diplômes</h2>
                        <p class="text-[11px] text-slate-500">Parcours professionnel</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                            <x-lucide-calendar-check class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Prise de service</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => $firstJob,
                                'text-slate-500 italic' => !$firstJob,
                            ])>{{ $v($firstJob) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                            <x-lucide-building class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Corps</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->body),
                                'text-slate-500 italic' => $empty($user->body),
                            ])>{{ $v($user->body) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <x-lucide-shield-check class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Statut officiel</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->official_status),
                                'text-slate-500 italic' => $empty($user->official_status),
                            ])>{{ $v($user->official_status) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                            <x-lucide-file-text class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Réf. recrutement</p>
                            <p @class([
                                'text-sm font-medium truncate font-mono',
                                'text-white' => !$empty($user->references_of_recrutement),
                                'text-slate-500 italic font-sans' => $empty(
                                    $user->references_of_recrutement),
                            ])>{{ $v($user->references_of_recrutement) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
                            <x-lucide-graduation-cap class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Diplôme académique
                            </p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->academic_graduate),
                                'text-slate-500 italic' => $empty($user->academic_graduate),
                            ])>{{ $v($user->academic_graduate) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                            <x-lucide-award class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Diplôme professionnel
                            </p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->professionnal_graduate),
                                'text-slate-500 italic' => $empty($user->professionnal_graduate),
                            ])>{{ $v($user->professionnal_graduate) }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Droite --}}
        <div class="lg:col-span-5 space-y-5">

            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center text-amber-400">
                        <x-lucide-id-card class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Pièce d’identité</h2>
                        <p class="text-[11px] text-slate-500">Documents officiels</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 space-y-2.5">
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <x-lucide-hash class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">N° matricule</p>
                            <p @class([
                                'text-sm font-medium truncate font-mono',
                                'text-white' => !$empty($user->matricule_number),
                                'text-slate-500 italic font-sans' => $empty($user->matricule_number),
                            ])>{{ $v($user->matricule_number) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400">
                            <x-lucide-badge class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">Type de pièce</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->ID_card_type),
                                'text-slate-500 italic' => $empty($user->ID_card_type),
                            ])>{{ $v($user->ID_card_type) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-400">
                            <x-lucide-scan class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">N° de la pièce</p>
                            <p @class([
                                'text-sm font-medium truncate font-mono',
                                'text-white' => !$empty($user->ID_card_numder),
                                'text-slate-500 italic font-sans' => $empty($user->ID_card_numder),
                            ])>{{ $v($user->ID_card_numder) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-lime-500/10 border border-lime-500/20 flex items-center justify-center text-lime-400">
                            <x-lucide-map-pin class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">Lieu de délivrance</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => !$empty($user->ID_card_delivery_place),
                                'text-slate-500 italic' => $empty($user->ID_card_delivery_place),
                            ])>{{ $v($user->ID_card_delivery_place) }}</p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3">
                        <div
                            class="h-8 w-8 shrink-0 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400">
                            <x-lucide-calendar-x class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500">Expiration</p>
                            <p @class([
                                'text-sm font-medium truncate',
                                'text-white' => $idExpired,
                                'text-slate-500 italic' => !$idExpired,
                            ])>{{ $v($idExpired) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-orange-500/15 border border-orange-500/25 flex items-center justify-center text-orange-400">
                        <x-lucide-trending-up class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Grades & indices</h2>
                        <p class="text-[11px] text-slate-500">Situation indiciaire</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3.5 text-center">
                        <div
                            class="mx-auto mb-2 h-8 w-8 rounded-lg bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400">
                            <x-lucide-medal class="w-3.5 h-3.5" />
                        </div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Grade actuel</p>
                        <p @class([
                            'text-sm font-semibold',
                            'text-white' => !$empty($user->current_grade),
                            'text-slate-500 italic font-medium' => $empty($user->current_grade),
                        ])>{{ $v($user->current_grade) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3.5 text-center">
                        <div
                            class="mx-auto mb-2 h-8 w-8 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <x-lucide-hash class="w-3.5 h-3.5" />
                        </div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Indice actuel</p>
                        <p @class([
                            'text-sm font-semibold font-mono',
                            'text-white' => !$empty($user->current_index),
                            'text-slate-500 italic font-medium font-sans' => $empty(
                                $user->current_index),
                        ])>{{ $v($user->current_index) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3.5 text-center">
                        <div
                            class="mx-auto mb-2 h-8 w-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <x-lucide-wallet class="w-3.5 h-3.5" />
                        </div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Grade payé</p>
                        <p @class([
                            'text-sm font-semibold',
                            'text-white' => !$empty($user->payed_grade),
                            'text-slate-500 italic font-medium' => $empty($user->payed_grade),
                        ])>{{ $v($user->payed_grade) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/[0.02] border border-white/[0.04] px-3.5 py-3.5 text-center">
                        <div
                            class="mx-auto mb-2 h-8 w-8 rounded-lg bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-400">
                            <x-lucide-coins class="w-3.5 h-3.5" />
                        </div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Indice payé</p>
                        <p @class([
                            'text-sm font-semibold font-mono',
                            'text-white' => !$empty($user->payed_index),
                            'text-slate-500 italic font-medium font-sans' => $empty($user->payed_index),
                        ])>{{ $v($user->payed_index) }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-white/[0.05] flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-500/15 border border-slate-500/25 flex items-center justify-center text-slate-400">
                        <x-lucide-sticky-note class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Observations</h2>
                        <p class="text-[11px] text-slate-500">Notes sur le dossier</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6">
                    <p @class([
                        'text-sm leading-relaxed',
                        'text-slate-300' => !$empty($user->observations),
                        'text-slate-500 italic' => $empty($user->observations),
                    ])>
                        {{ $v($user->observations) }}
                    </p>
                    <p class="mt-4 text-[11px] text-slate-600 flex items-center gap-1.5">
                        <x-lucide-calendar-plus class="w-3 h-3" />
                        Inscrit le {{ $v($createdFormatted) }}
                    </p>
                </div>
            </section>

            <section class="rounded-3xl border border-white/[0.07] bg-[#0c1220] p-5 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="h-9 w-9 rounded-xl bg-cyan-500/15 border border-cyan-500/25 flex items-center justify-center text-cyan-400">
                        <x-lucide-zap class="w-4 h-4" />
                    </div>
                    <h2 class="text-sm font-semibold text-white">Actions</h2>
                </div>
                <div class="flex flex-col gap-2">
                    <a wire:navigate href="{{ route('tenant.update.user.data') }}"
                        class="h-11 px-4 rounded-xl text-sm font-medium bg-indigo-500/15 hover:bg-indigo-500/25 text-indigo-200 border border-indigo-500/25 flex items-center gap-2.5 transition-all">
                        <x-lucide-user-pen class="w-4 h-4" /> Éditer mon profil
                    </a>
                    <a href="{{ route('tenant.update.profil.photo') }}"
                        class="h-11 px-4 rounded-xl text-sm font-medium bg-sky-500/10 hover:bg-sky-500/20 text-sky-300 border border-sky-500/20 flex items-center gap-2.5 transition-all">
                        <x-lucide-camera class="w-4 h-4" /> Modifier la photo
                    </a>
                    <a href="{{ route('tenant.update.password') }}"
                        class="h-11 px-4 rounded-xl text-sm font-medium bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/20 flex items-center gap-2.5 transition-all">
                        <x-lucide-key-round class="w-4 h-4" /> Changer le mot de passe
                    </a>
                    <button type="button" wire:click="removePhoto" wire:loading.attr="disabled"
                        class="h-11 px-4 rounded-xl text-sm font-medium bg-orange-500/10 hover:bg-orange-500/20 text-orange-300 border border-orange-500/20 flex items-center gap-2.5 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="removePhoto" class="inline-flex items-center gap-2.5">
                            <x-lucide-trash class="w-4 h-4" /> Retirer la photo
                        </span>
                        <span wire:loading wire:target="removePhoto" class="inline-flex items-center gap-2.5">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> En cours…
                        </span>
                    </button>
                    <button type="button" wire:click="logout"
                        class="h-11 px-4 rounded-xl text-sm font-medium bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 flex items-center gap-2.5 transition-all">
                        <x-lucide-log-out class="w-4 h-4" /> Déconnexion
                    </button>
                </div>
            </section>
        </div>
    </div>
</div>

