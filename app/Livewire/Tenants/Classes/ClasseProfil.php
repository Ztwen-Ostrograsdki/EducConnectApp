<?php

namespace App\Livewire\Tenants\Classes;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Profil de classe ou groupe pédagogique')]
class ClasseProfil extends Component
{
    public $section = 'classe-home-page';

    public $classroom = 'TERMINALE';

    public ?string $student_uuid_selected;

    public string $classe_slug;

    public ?string $period_type_selected;

    public function mount(string $classe_slug)
    {
        $this->classe_slug = $classe_slug;
    }

    public function setSection(string $section)
    {
        session()->put('tenant_classe_section_selected', $section);

        $this->section = $section;
    }

    public function updatedStudentUuidSelected(?string $student_uuid_selected)
    {

        session()->put('tenant_classe_bulletin_student_uuid_selected', $student_uuid_selected);

    }

    public function updatedPeriodTypeSelected(?string $period_type_selected)
    {
        session()->put('tenant_classe_bulletin_period_type_selected', $period_type_selected);

    }

    public function reloadStudentBulletin()
    {
        $this->dispatch('ReloadTheStudentBulletin', $this->period_type_selected, $this->student_uuid_selected);
    }

    public function resetBulletinSelections()
    {
        $this->reset('student_uuid_selected', 'period_type_selected');

        session()->forget('tenant_classe_bulletin_period_type_selected');

        session()->forget('tenant_classe_bulletin_student_uuid_selected');

        $this->dispatch('ReloadTheStudentBulletin', null, null);
    }

    public function render()
    {
        if (session()->has('tenant_classe_section_selected')) {

            $this->section = session('tenant_classe_section_selected');
        }

        if (session()->has('tenant_classe_bulletin_period_type_selected')) {

            $this->period_type_selected = session('tenant_classe_bulletin_period_type_selected');
        }

        if (session()->has('tenant_classe_bulletin_student_uuid_selected')) {

            $this->student_uuid_selected = session('tenant_classe_bulletin_student_uuid_selected');
        }

        return view('livewire.tenants.classes.classe-profil');
    }
}
