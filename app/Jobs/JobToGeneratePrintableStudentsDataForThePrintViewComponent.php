<?php

namespace App\Jobs;

use App\Services\PDFFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class JobToGeneratePrintableStudentsDataForThePrintViewComponent implements ShouldQueue
{
    use Queueable;

    public array $defaultConfigs = [
        'classe_id' => null,
        'serial_id' => null,
        'filiar_id' => null,
        'promotion_id' => null,
        'onlyTrashed' => false,
        'withoutTrashed' => true,
        'withLeaves' => false,
        'onlyLeaves' => false,
        'hasClasse' => false,
        'withHasntClasse' => false,
        'gender' => null,
        'observationColumn' => true,
        'options' => [
            'is_active' => true,
            'blocked' => false,
        ],
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string     $tenantId,
        public int        $notifiableId,
        public string     $docTitle = 'liste apprenants',
        public array      $configs = [],

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





        } catch (\Throwable $th) {





        }
        finally{

            if ($this->tenantId) tenancy()->end();
        }
    }


    public function factoryBuilder()
    {
        $students = [];

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
            notifiableId:    $this->notifiableId
        );

    }
}
  