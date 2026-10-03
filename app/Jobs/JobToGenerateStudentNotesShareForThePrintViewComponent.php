<?php

namespace App\Jobs;

use App\Helpers\Support\TenantStorage;
use App\Models\Classe;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\MarksServices\NotesShareQuery;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Timeout(120)]
class JobToGenerateStudentNotesShareForThePrintViewComponent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public ?SchoolYear $schoolYear;

    public function __construct(
        public string  $tenantId,
        public int     $notifiableId,
        public int     $student_id,
        public int     $period,
        public ?int    $subject_id = null,
        public ?int    $school_year_id = null,
        public ?string $space_url = null,
    ) {}

    public function handle(): void
    {
        if ($this->tenantId) {
            tenancy()->initialize($this->tenantId);
        }

        try {

            $this->checkSchoolYear();
            
            $this->factoryBuilder();

            $this->deleteAllOtherMarksDetails();

        } catch (\Throwable $th) {

            $director = User::first();

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ECHEC DE LA GENERATION DES NOTES",
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        } finally {
            if ($this->tenantId) tenancy()->end();
        }
    }

    public function checkSchoolYear(): void
    {
        if (! $this->school_year_id) {
            $schoolYear = SchoolYear::current()->first();
            if ($schoolYear) { $this->schoolYear = $schoolYear; $this->school_year_id = $schoolYear->id; }
        } else {
            $schoolYear = SchoolYear::firstWhere('id', $this->school_year_id);
            if ($schoolYear && $schoolYear->is_active) {
                $this->schoolYear = $schoolYear; $this->school_year_id = $schoolYear->id;
            } else {
                $director = User::first();

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA GENERATION DU DOCUMENT",
                    message:   "Année scolaire non définie ou introuvable",
                    type:      'error',
                ));
                $this->fail("Année scolaire non définie ou introuvable");
            }
        }
    }


    public function deleteAllOtherMarksDetails()
    {
        $docs = GeneratedDocument::where('type', 'notes_share')
            ->where('tenant_id', $this->tenantId)
            ->where('for_student_id', $this->student_id)
            ->get();

        if(count($docs)){

            foreach($docs as $doc){

                TenantStorage::delete($this->relativePathFromAbsolute($doc->path));

                $doc->delete();

            }
        }
    }

    /**
     * TenantStorage::delete() attend un chemin relatif au disque 'public',
     * alors que GeneratedDocument::path stocke un chemin absolu (issu de
     * PDFFactory::outputPath()). On reconvertit ici.
     */
    protected function relativePathFromAbsolute(string $absolutePath): string
    {
        $absolutePath = str_replace('\\', '/', $absolutePath);
        $storageRoot  = str_replace('\\', '/', Storage::disk('public')->path(''));

        return ltrim(Str::after($absolutePath, $storageRoot), '/');
    }

    public function factoryBuilder(): void
    {
        $student = Student::find($this->student_id);

        if(!$student) {

            $this->fail();

            return;
        }

        if(count($student->tutors) < 1){

            $this->fail();

            return;
        }

        $schoolYearId = $this->schoolYear->id;

        $schoolYear = $this->schoolYear;

        $subject = $this->subject_id ? Subject::find($this->subject_id) : null;

        $classeId = NotesShareQuery::resolveClasseIdForStudent($student->id, $schoolYearId);

        $classe = $classeId ? Classe::find($classeId) : null;

        $marksData = NotesShareQuery::getMarksData($student->id, $this->subject_id, $this->period, $schoolYearId);

        $docTitle = NotesShareQuery::resolveDocTitle($student, $subject, $this->period, $schoolYear);

        $viewData = [
            'student'    => $student,
            'classe'     => $classe,
            'marksData'  => $marksData,
            'printed_at' => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'  => $docTitle,
            'target'     => 'notes_share',
            'eventName'  => 'NotesSharePDFCompletedSuccessfullyLiveEvent',
            'period'     => $this->period,
            'schoolYear' => $schoolYear,
        ];

        PDFFactory::dispatch(
            view:           'livewire.tenants.students.notes-share-preview-component',
            data:            $viewData,
            filename:        Str::slug($docTitle),
            category:        'students',
            overrides:       ['landscape' => false, 'format' => 'A4'],
            documentType:    'notes_share',
            tenantId:        $this->tenantId,
            notifiableId:    $this->notifiableId,
            docDBInfos:      [
                'period'                    => $this->period, 
                'for_student_id'            => $this->student_id, 
                'title_for_sendable_document' => $docTitle,
                'space_url' => $this->space_url
            ],
            receiverIds: self::getReceiversIds(),
        );
    }


    public function getReceiversIds() : ?array
    {
        $student = Student::find($this->student_id);

        if($student) return $student->parents()->pluck('id')->toArray();

        return [];
    }
}