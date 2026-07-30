<?php

namespace App\Livewire\Tenants\Serials;


use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Services\SerialsServices\SerialDetailsCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
class SerialProfil extends Component
{
    use WireUiActions, WithPagination;

    public ?Serial $serial;

    public string $serial_slug;

    public array $details = [];

    public string $serial_name = 'Nom de la série';

    public ?string $school_year_selected;

    public $counter = 0;


    public function mount(string $serial_slug)
    {

        if(!$serial_slug) return abort(404);

        $this->serial_slug  = $serial_slug;

        $serial = Serial::withTrashed()->whereSlug($serial_slug)?->first();

        if(!$serial) return abort(404);

        $this->serial       = $serial;

        $this->serial_name       = $serial->name;

        $this->details = app(SerialDetailsCacheService::class)->get($this->serial->id);

    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }


    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return $this->serial->classes()->where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function subjects()
    {
        return $this->serial?->getSerialSubjectsOfSchoolYear()->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotions()
    {
        return $this->serial?->promotions;
    }

    #[Computed]
    public function teachers()
    {
        return $this->serial->getSerialTeachersOfSchoolYear()
                            ->orderBy('users.name')
                            ->orderBy('users.prenames')
                            ->get();
    }

    
    #[Computed]
    public function students()
    {
        return $this->serial->getSerialStudentsOfSchoolYear()
                            ->orderBy('students.name')
                            ->orderBy('students.prenames')
                            ->get();
    }

    public function deleteSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette Série ? ",
            'text'               => "Cette action enverra la Série dans la corbeille, alors cette Série ne sera plus disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSerial',
            'onConfirmedParams'  => ['serial' => $serialId],
        ]);
    }

    #[On('ConfirmToDeleteSerial')]
    public function OnConfirmToDeleteSerial(int $serialId): void
    {
        $serial = Serial::find($serialId);

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->delete();

            if($done){

                $this->notification()->success(
                    title: 'Série supprimée',
                    description: "La Série {$serial->name} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

	public function restoreSerial(int $serialId): void
    {
        $this->dispatch('swal', [
            'title'              => "Recuperer cette Série ? ",
            'text'               => "Cette action restorera la Série de la corbeille, alors cette Série sera disponible et active",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Recuperer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreSerial',
            'onConfirmedParams'  => ['serialId' => $serialId],
        ]);
    }

    #[On('ConfirmToRestoreSerial')]
    public function OnConfirmToRestoreSerial(int $serialId): void
    {
        $serial = Serial::withoutTrashed()->whereId($serialId)->first();

        if (!$serial) {

            $this->notification()->error(title: 'Série introuvable');
            return;
        }

        try {
            
            $done = $serial->restore();

            if($done){

                $this->notification()->success(
                    title: 'Série restorée',
                    description: "La Série {$serial->name} a été restorée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Série non restorée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Série non restorée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 200),
            );
        }
        
        
    }

    public function render()
    {
        return view('livewire.tenants.serials.serial-profil');
    }
}
