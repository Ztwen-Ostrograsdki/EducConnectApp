<div class="min-h-screen bg-[#070a12] text-slate-100">

    <div class="max-w-[1600px] mx-auto px-4 py-6 sm:py-8 space-y-6">

        @livewire('tenants.Components.classe-header-details', ['classe' => $this->classe, 'subject' => $this->subject])

        <div>
            @livewire('tenants.components.classe-time-plan-component', ['classe_slug' => $this->classe->slug, 'classeId' => $this->classe->id])
        </div>
    </div>

</div>

