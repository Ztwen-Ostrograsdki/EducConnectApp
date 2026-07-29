<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Events\NewSchoolYearActivatedEvent;
use App\Events\SchoolYearDesactivatedEvent;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait SchoolYearsActions{

	use WireUiActions;

    public function closePeriods($schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Fermer tous les semestres/trimestres de l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "Aucune saisie de notes de classe ne sera plus possible au cours de  l'année scolaire {$schoolYearSlug} jusqu'a la réactivation. L'édition de notes sera également verrouillée",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, fermer les semestres/trimestres',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToCloseSchoolYearPeriods',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToCloseSchoolYearPeriods')]
    public function OnCloseSchoolYearPeriods(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::firstWhere('slug', $schoolYearSlug);

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable en base de données");
            return;
        }

        try {

            $label = $schoolYear->periodLabel();
            
            $done = $schoolYear->update(['active_period' => null]);

            if($done){

                $this->notification()->success(
                    title: "Les {$label}s de {$schoolYearSlug} ont été fermés",
                    description: "L'insertion et l'édition des notes par les enseignants sont verrouillées jusqu'à la réactivation!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: "Les {$label}s de {$schoolYearSlug} n'ont pas été fermés",
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Les périodes de l'année scolaire {$schoolYearSlug} n'ont pas été fermés",
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }

	
	public function closeSchoolYear( $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Cloturer l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "Cette action rendra l'année scolaire {$schoolYearSlug} indisponible et les profs n'auront plus d'actions possible sur les différentes classes et apprenants!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, clôturer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToCloseSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToCloseSchoolYear')]
    public function OnCloseSchoolYear(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::firstWhere('slug', $schoolYearSlug);

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable en base de données");
            return;
        }

        try {
            
            $done = $schoolYear->update(['is_closed' => true]);

            if($done){

                $this->notification()->success(
                    title: 'Année scolaire clôturée',
                    description: "L'année scolaire {$schoolYear->slug} a été clôturée!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Année scolaire non clôturée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Année scolaire non clôturée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


	public function reopenSchoolYear(string $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Réouvrir l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "Cette action rendra l'année scolaire {$schoolYearSlug} disponible de nouveau ; les profs auront les possibilités d'actions sur les différentes classes et apprenants!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réouvrir',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToReopenSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToReopenSchoolYear')]
    public function OnReopenSchoolYear(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::firstWhere('slug', $schoolYearSlug);

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable en base de données");
            return;
        }

        try {
            
            $done = $schoolYear->update(['is_closed' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Année scolaire réouverte',
                    description: "L'année scolaire {$schoolYear->slug} a été réouverte!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Année scolaire non réouverte',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Année scolaire non réouverte',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


	public function activateSchoolYear(string $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Réactiver l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "L'année scolaire {$schoolYearSlug} sera donc l'année active, et toutes les actions seront désormais exercées sur cette année scolaire",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Réactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToReactivateSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToReactivateSchoolYear')]
    public function OnReactivateSchoolYear(string $schoolYearSlug): void
    {
        try {
            $school_year = SchoolYear::where('slug', $schoolYearSlug)->first();

            if (! $school_year) {
                $this->notification()->error(
                    title: 'Année Scolaire introuvable',
                    description: "L'année scolaire {$schoolYearSlug} est introuvable!",
                );
                return;
            }

            DB::transaction(function () use($school_year) {

                SchoolYear::where('is_active', true)
                    ->where('id', '<>', $school_year->id)
                    ->update(['is_active' => false]);

                $school_year->update(['is_active' => true]);
            });

            $this->notification()->success(
                title: 'Année activée',
                description: "L'année scolaire {$schoolYearSlug} a été activée avec succès !",
            );

            session()->put('activeSchoolYear', $schoolYearSlug);

            broadcast(new NewSchoolYearActivatedEvent(
                    tenantId: tenant('id'), 
                    school_year_slug: $schoolYearSlug, 
                    schoolYearId: $school_year->id
                )
            );

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Une erreur s'est produite",
                description: "L'année scolaire {$schoolYearSlug} n'a pas pu être activée : " . cutter($th->getMessage(), 2000),
            );
        }
    }


	public function deactivateSchoolYear(string $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Désactiver l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "L'année scolaire {$schoolYearSlug} ne sera plus l'année scolaire courante. Aucune année ne sera active tant qu'une autre n'aura pas été activée! Cette action déconnectera tous les enseignants, parents, élèves systématiquement!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Désactiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeactivateSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToDeactivateSchoolYear')]
    public function OnDeactivateSchoolYear(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::firstWhere('slug', $schoolYearSlug);

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable en base de données");
            return;
        }

        if (! $schoolYear->is_active) {
            $this->notification()->error(
                title: 'Action impossible',
                description: "L'année scolaire {$schoolYearSlug} n'est pas active.",
            );
            return;
        }

        try {

            $done = $schoolYear->update(['is_active' => false]);

            if($done){

                $this->notification()->success(
                    title: 'Année désactivée',
                    description: "L'année scolaire {$schoolYear->slug} a été désactivée!",
                );

                session()->forget('activeSchoolYear');

                broadcast(new SchoolYearDesactivatedEvent(tenant('id'), $schoolYear->slug));

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Année scolaire non désactivée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Année scolaire non désactivée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }


	public function deleteSchoolYear(string $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "Cette action enverra l'année scolaire dans la corbeille, toutes les données liées à cette année ne seront plus disponibles",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, mettre en corbeille',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToDeleteSchoolYear')]
    public function OnConfirmToDeleteSchoolYear(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::firstWhere('slug', $schoolYearSlug);

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable en base de données");
            return;
        }

        try {
            
            // $done = $schoolYear->delete();
            $done = rand(0, 1);

            if($done){

                $this->notification()->success(
                    title: "L'année scolaire {$schoolYearSlug} supprimée",
                    description: "L'année scolaire {$schoolYearSlug} a été envoyée dans la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: 'Année scolaire non supprimée',
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Année scolaire non supprimée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }

	public function restoreSchoolYear(string $schoolYearSlug): void
    {
        $this->dispatch('swal', [
            'title'              => "Récupérer l'année scolaire {$schoolYearSlug} ? ",
            'text'               => "Cette action restaurera l'année scolaire {$schoolYearSlug} de la corbeille, les données de cette année seront de nouveau disponibles",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Récupérer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreSchoolYear',
            'onConfirmedParams'  => ['schoolYearSlug' => $schoolYearSlug],
        ]);
    }

    #[On('ConfirmToRestoreSchoolYear')]
    public function OnConfirmToRestoreSchoolYear(string $schoolYearSlug): void
    {
        $schoolYear = SchoolYear::onlyTrashed()->where('slug', $schoolYearSlug)->first();

        if (!$schoolYear) {

            $this->notification()->error(title: "L'année scolaire {$schoolYearSlug} est introuvable dans la corbeille");
            return;
        }

        try {
            
            $done = $schoolYear->restore();

            if($done){

                $this->notification()->success(
                    title: 'Année scolaire restaurée',
                    description: "L'année scolaire {$schoolYearSlug} a été restaurée de la corbeille!",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
            }
            else{
                $this->notification()->error(
                    title: "Année scolaire {$schoolYearSlug} non restaurée",
                    description: "Une erreur est survenue, veuillez réessayer!",
                );
            }

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Année scolaire non restaurée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
        
    }
}