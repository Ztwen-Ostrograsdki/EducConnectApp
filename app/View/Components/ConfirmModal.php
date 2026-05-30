<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $show = false,
        public string $title = 'Confirmation',
        public string $confirmText = 'Confirmer',
        public string $cancelText = 'Annuler',
        public string $confirmAction = '',
        public string $closeAction = ''
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.confirm-modal');
    }
}
