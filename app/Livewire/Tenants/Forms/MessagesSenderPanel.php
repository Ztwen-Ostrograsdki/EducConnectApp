<?php

namespace App\Livewire\Tenants\Forms;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class MessagesSenderPanel extends Component
{
    public function render()
    {
        return view('livewire.tenants.forms.messages-sender-panel');
    }
}
