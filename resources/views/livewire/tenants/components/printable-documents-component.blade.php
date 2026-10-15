<div class="flex items-center gap-2 overflow-x-auto pb-1 flex-wrap">
    @if (__check_route('tenant.students.docs'))
        <a wire:navigate href="{{ route('tenant.students.docs') }}"
            @if (request()->routeIs('tenant.students.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.students.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-users class="w-3.5 h-3.5" />
            Apprenants
        </a>
    @endif

    @if (__check_route('tenant.teachers.docs'))
        <a wire:navigate href="{{ route('tenant.teachers.docs') }}"
            @if (request()->routeIs('tenant.teachers.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.teachers.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-graduation-cap class="w-3.5 h-3.5" />
            Enseignants
        </a>
    @endif

    @if (__check_route('tenant.classes.docs'))
        <a wire:navigate href="{{ route('tenant.classes.docs') }}"
            @if (request()->routeIs('tenant.classes.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.classes.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-school class="w-3.5 h-3.5" />
            Classes
        </a>
    @endif

    @if (__check_route('tenant.notes.docs'))
        <a wire:navigate href="{{ route('tenant.notes.docs') }}"
            @if (request()->routeIs('tenant.notes.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.notes.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-clipboard-list class="w-3.5 h-3.5" />
            Notes
        </a>
    @endif

    @if (__check_route('tenant.students.bests.weaks.docs'))
        <a wire:navigate href="{{ route('tenant.students.bests.weaks.docs') }}"
            @if (request()->routeIs('tenant.students.bests.weaks.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.students.bests.weaks.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-trophy class="w-3.5 h-3.5" />
            Classements
        </a>
    @endif

    @if (__check_route('tenant.timeplans.docs'))
        <a wire:navigate href="{{ route('tenant.timeplans.docs') }}"
            @if (request()->routeIs('tenant.timeplans.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.timeplans.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-calendar-range class="w-3.5 h-3.5" />
            Emplois du temps
        </a>
    @endif

    @if (__check_route('tenant.stats.docs'))
        <a wire:navigate href="{{ route('tenant.stats.docs') }}"
            @if (request()->routeIs('tenant.stats.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.stats.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-bar-chart-3 class="w-3.5 h-3.5" />
            Stats
        </a>
    @endif

    @if (__check_route('tenant.bulletins.docs'))
        <a wire:navigate href="{{ route('tenant.bulletins.docs') }}"
            @if (request()->routeIs('tenant.bulletins.docs')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.bulletins.docs')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-file-badge class="w-3.5 h-3.5" />
            Bulletins
        </a>
    @endif

    @if (__check_route('tenant.documents.page'))
        <a wire:navigate href="{{ route('tenant.documents.page') }}"
            @if (request()->routeIs('tenant.documents.page')) aria-current="page" @endif
            class="shrink-0 h-9 px-3.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition-all
                {{ request()->routeIs('tenant.documents.page')
                    ? 'bg-sky-500/80 text-black border border-white/20 shadow-sm ring-1 ring-white/10'
                    : 'bg-white/5 text-slate-400 border border-transparent hover:bg-white/10 hover:text-slate-200' }}">
            <x-lucide-folder-open class="w-3.5 h-3.5" />
            Partagés - Public
        </a>
    @endif
</div>

