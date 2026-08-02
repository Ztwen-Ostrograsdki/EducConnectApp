<div>

    {{-- @livewire('tenants.users.parent.parent-students-skills-header-component', ['student' => $this->student]) --}}

    @livewire('tenants.components.bulletin-component', ['student' => $this->student, 'classe' => $this->currentClasse])
</div>

