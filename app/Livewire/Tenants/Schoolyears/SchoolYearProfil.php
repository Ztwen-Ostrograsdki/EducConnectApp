<?php

namespace App\Livewire\Tenants\Schoolyears;

use App\Events\NewSchoolYearActivatedEvent;
use App\Events\SchoolYearUpdatedEvent;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Profil année scolaire')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class SchoolYearProfil extends Component
{
    use WireUiActions;

    public ?string $school_year_slug;

    public ?string $school_year_uuid;

    public ?SchoolYear $school_year_model;


    public int $counter = 0;

    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }


    public function mount(string $school_year)
    {
        if(!$school_year) return abort(404);

        $schoolYear = SchoolYear::whereSlug($school_year)->first();

        if(!$schoolYear) return abort(404);

        $this->school_year_model = $schoolYear;

        $this->school_year_slug = $schoolYear->slug;

        $this->school_year_uuid = $schoolYear->uuid;
        
    }


    public function activateSchoolYear()
    {
        try {
            DB::transaction(function () {
                $school_year = SchoolYear::where('slug', $this->school_year_slug)->first();

                if (! $school_year) {
                    $this->notification()->error(
                        title: 'Année Scolaire introuvable',
                        description: "L'année scolaire {$this->school_year_slug} est introuvable!",
                    );
                }

                SchoolYear::where('is_active', true)
                    ->where('id', '!=', $school_year->id)
                    ->update(['is_active' => false]);

                $school_year->update(['is_active' => true]);
            });

            $this->notification()->success(
                title: 'Année activée',
                description: "L'année scolaire {$this->school_year_slug} a été activée avec succès !",
            );

            broadcast(new NewSchoolYearActivatedEvent(tenant('id'), $this->school_year_slug));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Une erreur s'est produite",
                description: "L'année scolaire {$this->school_year_slug} n'a pas pu être activée : " . cutter($th->getMessage(), 150),
            );
        }
    }


    public function desactivateSchoolYear()
    {
        try {
            DB::transaction(function () {
                $school_year = SchoolYear::where('slug', $this->school_year_slug)->first();

                if (! $school_year) {
                    $this->notification()->error(
                        title: 'Année Scolaire introuvable',
                        description: "L'année scolaire {$this->school_year_slug} est introuvable!",
                    );
                }


                $school_year->update(['is_active' => false]);
            });

            $this->notification()->success(
                title: 'Année scolaire désactivée',
                description: "L'année scolaire {$this->school_year_slug} a été désactivée avec succès !",
            );

            broadcast(new SchoolYearUpdatedEvent(tenant('id'), $this->school_year_slug));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Une erreur s'est produite",
                description: "L'année scolaire {$this->school_year_slug} n'a pas pu être désactivée : " . cutter($th->getMessage(), 150),
            );
        }
        
    }

    public function closed()
    {
        try {

            $school_year = SchoolYear::where('slug', $this->school_year_slug)->first();

            if (! $school_year) {

                $this->notification()->error(
                    title: 'Année Scolaire introuvable',
                    description: "L'année scolaire {$this->school_year_slug} est introuvable!",
                );
            }

            $school_year->update(['is_active' => false, 'is_closed' => true]);

            $this->notification()->success(
                title: 'Année scolaire fermée',
                description: "L'année scolaire {$this->school_year_slug} a été fermée avec succès !",
            );

            broadcast(new SchoolYearUpdatedEvent(tenant('id'), $this->school_year_slug));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Une erreur s'est produite",
                description: "L'année scolaire {$this->school_year_slug} n'a pas pu être fermée : " . cutter($th->getMessage(), 150),
            );
        }
    }


    public function reOpen()
    {
        try {

            $school_year = SchoolYear::where('slug', $this->school_year_slug)->first();

            if (! $school_year) {
                
                $this->notification()->error(
                    title: 'Année Scolaire introuvable',
                    description: "L'année scolaire {$this->school_year_slug} est introuvable!",
                );
            }

            $school_year->update(['is_active' => false, 'is_closed' => false]);

            $this->notification()->success(
                title: 'Année scolaire réouverte',
                description: "L'année scolaire {$this->school_year_slug} a été réouverte avec succès !",
            );

            broadcast(new SchoolYearUpdatedEvent(tenant('id'), $this->school_year_slug));

        } catch (\Throwable $th) {

            $this->notification()->error(
                title: "Une erreur s'est produite",
                description: "L'année scolaire {$this->school_year_slug} n'a pas pu être réouverte : " . cutter($th->getMessage(), 150),
            );
        }
    }


    public function render()
    {
        return view('livewire.tenants.schoolyears.school-year-profil');
    }
}
