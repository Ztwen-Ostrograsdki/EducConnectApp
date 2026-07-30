<?php

namespace App\Livewire\Tenants\Subjects;

use App\Events\DataUpdatedEvent;
use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\YearlyPromotionSpecialitySubjectCoef;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;



#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Gestion des coefiscients")]
class ManagePromotionSpecialityCoefComponent extends Component
{
    use WireUiActions;

    public ?YearlyPromotionSpecialitySubjectCoef $coef_relation = null;

    public ?string $uuid = null;

    #[Validate('string|required')]
    public ?string $promotion = null;

    public ?string $subject_slug = null;

    public ?Subject $subject = null;

    public ?int $filiar_id  = null;

    public ?int $serial_id  = null;

    public ?int $subject_id  = null;

    #[Validate('integer|required|min:1')]
    public ?int $value  = 1;

    public ?string $error = null;


    public function mount(?string $subject_slug = null, ?string $uuid = null)
    {
        session()->put('from_url', url()->previous());

        if($subject_slug){

            $subject = Subject::firstWhere('slug', $subject_slug);

            if(!$subject) return abort(404);

            $this->subject = $subject;

            $this->subject_slug = $subject_slug;

            $this->subject_id = $subject->id;
        }

        if($uuid){

            $coef_relation = YearlyPromotionSpecialitySubjectCoef::firstWhere('uuid', $uuid);

            if(!$coef_relation) return abort(404);

            $this->coef_relation = $coef_relation;

            $this->subject = $coef_relation->subject;

            $this->promotion = $coef_relation->promotion;

            $this->filiar_id = $coef_relation->filiar_id;

            $this->serial_id = $coef_relation->serial_id;

            $this->value = $coef_relation->coef;

        }

        $promotion  = request('promotion');
        $filiar_id  = request('filiar_id');
        $serial_id  = request('serial_id');

        if($filiar_id){

            $this->filiar_id = $filiar_id;
        }

        if($serial_id){

            $this->serial_id = $serial_id;
        }

        if($promotion){

            $this->promotion = $promotion;
        }

    }


    public function updatedFiliarId(): void    
    { 
        $this->serial_id = null;

    }

    public function updatedSubjectId(?int $subject_id): void    
    { 

        if($subject_id) $this->subject = Subject::find($subject_id);

    }


    public function updatedSerialId(): void    
    { 
        $this->filiar_id = null;

    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function promotionInGroups()
    {
        return config('app.promotionInGroups');
    }


    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }


    #[Computed]
    public function subjects()
    {
        return Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
    }


    public function save()
    {
        $this->resetErrorBag();

        $this->error = null;

        try {
            if($this->filiar_id && $this->serial_id || (!$this->filiar_id && !$this->serial_id)){

                $this->addError('filiar_id', "Sélection ambigüe: filière ou série ?");

                $this->addError('serial_id', "Sélection ambigüe: filière ou série ?");

                $this->error = "Vous devez préciser soit la filière, soit la série!";

                $this->notification()->error(
                    title: "Une erreur s'est produite lors de la mise à jour du coéficient",
                    description: 'Erreur : ' . $this->error,
                );

                return;
            }

            if($this->serial_id){
                $this->validate([
                    'serial_id' => 'required|integer|exists:serials,id',
                ]);
            }
            elseif($this->filiar_id){
                $this->validate([
                    'filiar_id' => 'required|integer|exists:filiars,id',
                ]);
            }

            $this->validate([
                'promotion' => ['required', 'string', Rule::in($this->promotionInGroups)],
            ]);


            $query = YearlyPromotionSpecialitySubjectCoef::where('subject_id', $this->subject_id)->where('promotion', $this->promotion)->where('school_year_id', $this->activeYear->id);

            if($this->filiar_id) $query->where('filiar_id', $this->filiar_id);

            if($this->serial_id) $query->where('serial_id', $this->serial_id);

            if($this->coef_relation) $query->where('id', '<>', $this->coef_relation->id);

            $exists = $query->exists();

            if($exists){

                $this->error = "Il semble que ce coéfiscient soit déjà défini";

                $this->notification()->error(
                    title: "Une erreur s'est produite lors de la mise à jour du coéficient",
                    description: 'Erreur : ' . $this->error,
                );

                return;
            }

            $data  = [
                'subject_id' => $this->subject_id,
                'filiar_id' => $this->filiar_id,
                'serial_id' => $this->serial_id,
                'school_year_id' => $this->activeYear->id,
                'promotion' => $this->promotion
            ];

            YearlyPromotionSpecialitySubjectCoef::updateOrCreate($data, ['coef' => $this->value]);

            $this->notification()->success(
                title: 'MISE A JOUR DU COEFISCIENT REUSSIE',
                description: "Le coéficient de {$this->subject->name} a été mis à jour avec succès pour la promotion {$this->promotion}.",
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

            if($this->coef_relation){

                return $this->redirect(route('tenant.subject.profil', ['subject_slug' => $this->subject->slug]), navigate:true);
            }
            elseif(request('promotion') || request('filiar_id') || request('serial_id')){

                return $this->redirect(session('from_url'), navigate:true);
                
            }
            

            if($this->subject_slug) $this->reset('filiar_id', 'serial_id', 'promotion', 'value');

            else $this->reset();

        } catch (\Throwable $th) {
           
            $this->notification()->error(
                title: "Une erreur s'est produite lors de la mise à jour du coéficient",
                description: 'Erreur : ' . cutter($th->getMessage(), 1500),
            );
        }

    }


    public function render()
    {
        return view('livewire.tenants.subjects.manage-promotion-speciality-coef-component');
    }
}
