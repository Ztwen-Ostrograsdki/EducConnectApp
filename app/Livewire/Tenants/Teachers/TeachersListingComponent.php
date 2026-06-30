<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class TeachersListingComponent extends Component
{
    use WireUiActions, WithPagination, TeachersActions;

    public $teachers = [];

    public int $perPage = 30;
    

    public function render()
    {
        return view('livewire.tenants.teachers.teachers-listing-component');
    }
}
