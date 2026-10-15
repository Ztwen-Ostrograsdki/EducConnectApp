<?php

namespace App\Livewire\Tenants\Classes;

use App\Livewire\Tenants\ActionsTraits\DocumentActionsTrait;
use App\Models\Filiar;
use App\Models\GeneratedDocument;
use App\Models\Promotion;
use App\Models\Serial;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Gestion des documents générés sur les classes')]
class ClassesPrintableDocumentsPage extends Component
{
    use WireUiActions, WithPagination, DocumentActionsTrait;

    public string $search = '';

    public ?string $targeted = null;

    public string $targetRoute = 'tenant.classes.docs';
    
    public string $pageTitle = 'Documents générés - Liste classes';

    public $targetRoutes = [
        'tenant.students.docs' => 'Liste des apprenants',
        'tenant.classes.docs' => 'Liste des classes',
        'tenant.teachers.docs' => 'Liste des enseignants',
    ];



    public ?string $filiar_slug = null;
    public ?int $filiar_id = null;
    public ?Filiar $filiar = null;

    public ?string $serial_slug = null;
    public ?int $serial_id = null;
    public ?Serial $serial = null;

    public ?string $promotion_slug = null;
    public ?int $promotion_id = null;
    public ?Promotion $promotion = null;

    public ?string $promotionsGrouped = null;

    public $counter = 0;


    public function mount()
    {
        
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
        $this->refreshDocumentsList();
    }


    public function updatedTargetRoute(string $targetRoute)
    {
        if($targetRoute){

            $url = route($targetRoute);

            return $this->redirect($url, navigate:true);
        }
    }

    #[Computed]
    public function documents()
    {
        return GeneratedDocument::ofType('classe_list')
            ->forUser(auth('tenant')->id())
            ->when($this->search, fn ($q) =>
                $q->where('filename', 'like', '%' . $this->search . '%')
            )
            ->when($this->filiar, function($q){
                $q->where('filiar_id', $this->filiar->id);
            })
            ->when($this->serial, function($q){
                $q->where('serial_id', $this->serial->id);
            })
            ->when($this->promotion, function($q){
                $q->where('promotion_id', $this->promotion->id);
            })
            ->when($this->promotionsGrouped, function($q){
                $q->where('promotionsGrouped', $this->promotionsGrouped);
            })
            ->latest()
            ->paginate(9);
    }



    
    /**
     * Compatibilité avec l’ancien événement SweetAlert de cette page.
     */
    #[On('ConfirmToDeleteDoc')]
    public function deleteDocumentLegacy(int $documentId): void
    {
        $this->deleteDocument($documentId);
    }

    public function initiator(?string $filiar_slug = null, ?string $promotion_slug = null, ?string $promotionsGrouped = null, ?string $serial_slug = null)
    {
        if($filiar_slug){

            $filiar = Filiar::firstWhere('slug', $filiar_slug);

            if(!$filiar) return abort(404);

            $this->filiar = $filiar;

            $this->targeted = 'Filière ' . $filiar->name;

        }


        if($promotion_slug){

            $promotion = Promotion::firstWhere('slug', $promotion_slug);

            if(!$promotion) return abort(404);

            $this->promotion = $promotion;

            $this->targeted = 'Promotion ' . $promotion->name;

        }

        if($promotionsGrouped){

            $this->promotionsGrouped = $promotionsGrouped;

            $this->targeted = 'Promotion ' . $promotionsGrouped;

        }

        if($serial_slug){

            $serial = Serial::firstWhere('slug', $serial_slug);

            if(!$serial) return abort(404);

            $this->serial = $serial;

            $this->targeted = 'Série ' . $serial->name;

        }
    }

    public function render()
    {
        return view('livewire.tenants.classes.classes-printable-documents-page');
    }
}