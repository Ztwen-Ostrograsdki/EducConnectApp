<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Jobs\JobToGeneratePrintableBulletinsDataForThePrintViewComponent;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\BulletinsServices\BulletinPrintQuery;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;




trait StudentBulletinActions{

	#[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

	public function generateStudentBulletin (int $studentId)
    {
        $schoolYear = $this->activeYear;

        if (! $schoolYear || ! $schoolYear->active_period) {
            $this->notification()->error(title: "Aucune période active pour générer le bulletin.");
            return;
        }

        $student = Student::find($studentId);

        if(!$student) {

            $this->notification()->error(title: "Aucun apprenant correspondant trouvé dans la base de données!");
            return;
        }

        $classe = $student->classe;

        if (! $classe) {

            $this->notification()->error(title: "Cet apprenant n'a pas de classe active.");
            return;
        }

        $config = [
            'classe_id'    => $classe->id,
            'student_id'   => $student->id,
            'leavesConfig' => 'withLeaves',
        ];

        $docTitle = BulletinPrintQuery::resolveDocTitle($config, $schoolYear->active_period, $schoolYear);

        JobToGeneratePrintableBulletinsDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            period:         $schoolYear->active_period,
            school_year_id: $schoolYear->id,
            docTitle:       $docTitle,
            config:         $config,
        );

        $this->notification()->success(title: 'Génération du bulletin lancée');
    }



    #[Computed]
    public function hasCurrentBulletin(int $studentId) : bool
    {
        $doc = GeneratedDocument::where('type', 'bulletins_list')
            ->where('for_student_id', $studentId)
            ->where('school_year_id', $this->activeYear->id)
            ->where('period', $this->activeYear->active_period)
            ->latest()
			->first();

        return  $doc && File::exists($doc->path);
    }

    public function printStudentBulletin (int $studentId)
    {
        $doc = GeneratedDocument::where('type', 'bulletins_list')
            ->where('for_student_id', $studentId)
            ->where('school_year_id', $this->activeYear->id)
            ->where('period', $this->activeYear->active_period)
            ->latest()
			->first();

        if (! $doc || ! File::exists($doc->path)) {
            $this->notification()->error(
                title: 'Document introuvable',
                description: 'Le fichier a peut-être déjà été supprimé du serveur.',
            );
            return;
        }

        $doc->recordDownload();

        $this->notification()->info(title: 'Téléchargement du document en cours ...');

        return response()->download($doc->path, $doc->filename);
    }





	
}