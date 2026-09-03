<?php

namespace App\Livewire\Central\CentralTraits;

use Livewire\Attributes\On;




trait CentralReloaderTrait{


	public int $counter = 0;

    #[On('CentralDataUpdatedLiveEvent')]
    public function relaodData(): void
    {
        $this->counter++;
    }


	
}