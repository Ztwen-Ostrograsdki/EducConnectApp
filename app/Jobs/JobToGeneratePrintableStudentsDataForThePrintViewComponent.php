<?php

namespace App\Jobs;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

#[Timeout(300)]
class JobToGeneratePrintableStudentsDataForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public array $defaultConfigs = [
        'classe_id' => null,
        'serial_id' => null,
        'filiar_id' => null,
        'promotion_id' => null,
        
        'promotionsInGroup' => null,

        'onlyTrashed' => false,
        'withoutTrashed' => true,
        'withTrashed' => false,

        'withLeaves' => false,
        'onlyLeaves' => false,
        'onlyActives' => true,

        'onlyHasClasse' => true,
        'onlyHasntClasse' => false,
        'withHasntClasse' => false,

        'gender' => null,
        'observationColumn' => true,
        
    ];

    public array $studentsTypesActivesOrNot = [
        'onlyActives' => "Seulement apprenants actifs",
        'onlyLeaves' => "Seulement apprenants déclarés abandons",
        'withLeaves' => "Tous les apprenants abandons inclus",
    ];

    public array $studentsWithOrWithoutClasses = [
        'onlyHasClasse' => "Seulement apprenants ayant de classes",
        'onlyHasntClasse' => "Seulement apprenants n'ayant pas de classe",
        'withHasntClasse' => "Tous les apprenants ayant ou pas pas de classe",
    ];

    public ?SchoolYear $schoolYear;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string     $tenantId,
        public int        $notifiableId,
        public string     $docTitle = 'liste apprenants',
        public ?int     $school_year_id = null,
        public array      $config = [
            "trashedConfig" => 'withoutTrashed',
            "leavesConfig" => 'onlyActives',
            "hasClasseConfig" => 'onlyHasClasse',
            "classe_id" => null,
            "filiar_id" => null,
            "serial_id" => null,
            "promotion_id" => null,
            "promotionInGroups" => null,
            "gender" => null,
            "city" => null,
            "department" => null,
            'tableColumns' => [
                'name' => "Nom et Prénoms",
                'sexe' => "Sexe",
                'educMaster' => "EducMaster",
                'classe' => "Classe",
                'dateNaissance' => "Date de naissance - Age",
                'status' => "Statut",
                'observation' => "Observations",
            ],
        ],


    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->tenantId) tenancy()->initialize($this->tenantId);

        try {
            
            self::checkSchoolYear();

            self::factoryBuilder();


        } catch (\Throwable $th) {

            $director = User::firstWhere('tenant_id', $this->tenantId);

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC DE LA GENERATION DU DOCUMENT",
                message:   cutter($th->getMessage(), 200),
                type:      'error',
            ));


        }
        finally{

            if ($this->tenantId) tenancy()->end();
        }
    }

    public function checkSchoolYear()
    {
        if(!$this->school_year_id){

            $schoolYear = SchoolYear::current()->first();

            if($schoolYear){

                $this->schoolYear = $schoolYear;

                $this->school_year_id = $schoolYear->id;
            }
        }
        else{

            $schoolYear = SchoolYear::firstWhere($this->school_year_id);

            if($schoolYear && $schoolYear->is_active){

                $this->schoolYear = $schoolYear;

                $this->school_year_id = $schoolYear->id;
            }
            else{

                $director = User::firstWhere('tenant_id', $this->tenantId);

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA GENERATION DU DOCUMENT",
                    message:   "Année scolaire non définie ou introuvable",
                    type:      'error',
                ));

                $this->fail("Année scolaire non définie ou introuvable");

                return;
            }
        }
    }

    public function getStudentsIds() :  array
    {

        $query = Student::query()->select('students.id');

        if($this->config['trashedConfig']){

            $trashedConfig = $this->config['trashedConfig'];

            $query->{$trashedConfig}();

        }
        if($this->config['leavesConfig']){

            $leavesConfig = $this->config['leavesConfig'];

            if($leavesConfig === 'onlyLeaves'){

                $query->whereHas('yearlyStudentsLeaves', function($q){

                    $q->where('school_year_id', $this->school_year_id);

                });
            }
            elseif($leavesConfig === 'onlyActives'){

                $query->whereDoesntHave('yearlyStudentsLeaves', function($q){

                    $q->where('school_year_id', $this->school_year_id);

                });
            }

        }
        
        if($this->config['hasClasseConfig']){

            $hasClasseConfig = $this->config['hasClasseConfig'];

            if($hasClasseConfig === 'onlyHasClasse'){
                $query->whereHas('classes', function($q){
                    $q->where('school_year_id', $this->school_year_id)->where('is_active', true);

                });
            }
            elseif($hasClasseConfig === 'onlyHasntClasse'){

                $query->whereDoesntHave('classes', function($q){

                    $q->where('school_year_id', $this->school_year_id);

                });
            }

        }

        if($this->config['city']){

            $query->where('city', $this->config['city']);
        }
        if($this->config['gender']){

            $query->whereIn('gender', [$this->config['gender'], Str::lower($this->config['gender']), Str::upper($this->config['gender'])]);
        }

        if($this->config['department']){

            $query->where('department', $this->config['department']);
        }
        if($this->config['classe_id']){

            $query->whereHas('classes', fn($q) => 
                    $q->where('is_active', true)->where('classe_id', $this->config['classe_id'])->where('school_year_id', $this->school_year_id)
                );
        }

        if($this->config['promotion_id']){
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->school_year_id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('promotion_id', $this->config['promotion_id'])
                       ->where('is_active', true)
                       ->where('school_year_id', $this->school_year_id)
                  )
            );
        }

        if($this->config['promotionInGroups']){
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->school_year_id)
                  ->whereHas('classe', fn($qr0) => 
                    $qr0->whereHas('promotion', fn($qr) => 
                        $qr->where('name', $this->config['promotionInGroups'])
                            ->where('is_active', true)
                            ->where('school_year_id', $this->school_year_id)
                    )
                  )
            );
        }

        if($this->config['filiar_id']) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->school_year_id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('filiar_id', $this->config['filiar_id'])
                       ->where('is_active', true)
                       ->where('school_year_id', $this->school_year_id)
                  )
            );
        }

        if($this->config['serial_id']) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->school_year_id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('serial_id', $this->config['serial_id'])
                       ->where('is_active', true)
                       ->where('school_year_id', $this->school_year_id)
                  )
            );
        }
        return $query->toArray();
    }

    public function factoryBuilder()
    {
        $students = self::getStudentsIds();

        $printed_at  = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $viewData = [
            'students'        => $students,
            'printed_at'      => $printed_at,
            'allStudents'     => count($students),
            'totalActifs'     => count($students),
            'pdf_title'       => $this->docTitle,
            'target'          => 'students',
            'eventName'       => 'StudentsPDFCompletedSuccessfullyLiveEvent',
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.students.Students-printable-list-component',
            data:            $viewData,
            filename:        Str::slug($this->docTitle),
            category:        'students',
            overrides:       ['landscape' => true],
            documentType:    'student_list',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
            tableColumns: $this->config['tableColumns']
        );

    }
}
  