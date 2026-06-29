<?php

namespace App\Jobs;

use App\Mail\MailToTeacherForYearlyAccessGiven;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\TeacherYearlyAccess;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\EmailTemplateBuilder;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class JobToCreateYearlyAccessForTeacher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    /**
     * Nombre de tentatives max (retry manuel via bouton).
     */
    public int $tries = 1;

    /**
     * @param string $tenantId
     * @param int    $teacherId
     * @param int    $school_year_id
     */
    public function __construct(
        public string $tenantId,
        public int    $teacherId,
        public ?int    $school_year_id = null,
        public ?string $domain = null,
    ) {}

    /**
     * @return void
     */
    public function handle(): void
    {

        try {

            tenancy()->initialize($this->tenantId);

            $tenant = tenancy()->tenant;

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE CREER UN ACCES ENSEIGNANT SANS LE COMPTE DIRECTEUR");

                return;

            }

            if(!$this->school_year_id){

                $school_year = SchoolYear::current()?->first();

                $this->school_year_id = $school_year->id;
            }
            else{

                $school_year = SchoolYear::find($this->school_year_id);

            }

            $teacher = Teacher::find($this->teacherId);

            if(!$school_year || !$teacher || !$school_year?->is_active){

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA CREATION D'ACCES A L'ENSEIGNANT ",
                    message:   "Enseignant inexistant ou année scolaire inexistante ou non active!",
                    type:      'error',
                ));

                $this->fail("Enseignant inexistant ou année scolaire inexistante ou non active!");

                return;
            }

            if($teacher->hasValidAccessForYear($this->school_year_id)){

                $full_name = $teacher->getFullName();

                $error_message = "Echec de création d'accès à l'enseignant " . $full_name . " . Car, il possède déjà un accès pour cette année scolaire " . $school_year->slug;

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "ECHEC DE LA CREATION D'ACCES A L'ENSEIGNANT ",
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);

                return;

            }

            TeacherYearlyAccess::create([
                'teacher_id'                   => $this->teacherId,
                'school_year_id'               => $this->school_year_id,
                'token'                        => Str::uuid(),
                'token_expires_at'             => null,
                'validated_at'                 => now(),
                'token_requested_at'           => now(),
                'status'                       => 'active',
            ]);

            $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "ACCES ENSEIGNANT CREE AVEC SUCCES",
                    message:           "L'accès de l'enseignant " . $teacher->getUserNamePrefix(true, true) . " pour le compte de l'année scolaire " . $school_year->slug . " a été créé avec succès!",
                    type:              'success',
                )
            );

            $space_url = get_tenant_url($this->domain, 'login');

            $receiver_html = EmailTemplateBuilder::render('yearly-access-for-teacher-template', [
                'space_url'             => $space_url,
                'school_year'           => $school_year->slug,
                'for_greating'          => $teacher->user?->greating(),
                'full_name'             => $teacher?->getFullName(),
                'contacts'              => $teacher->contacts,
                'email'                 => $teacher->user->email,
                'gender'                => $teacher->user->gender,
                'school_name'           => $tenant?->school_name,
                'simple_name'           => $tenant?->simple_name,
                'school_type'           => $tenant?->school_type,
                'enseignement_type'     => $tenant?->enseignement_type,
                'periode_type'          => $tenant?->periode_type,
                'adresse'               => $tenant?->adresse,
                'domain'                => $tenant?->getDomainName(),
            ]);

            Mail::to($teacher->user->email)->queue(
                new MailToTeacherForYearlyAccessGiven(
                    school_year: $school_year->slug, 
                    html: $receiver_html
                )
            );

            try {

               JobToSendCredentialsToUser::dispatch($this->tenantId, $teacher->user->email, null, $space_url);

            } catch (\Throwable $e) {

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE L'ENVOI DES DONNEES ESPACE ENSEIGNANT A : " . $teacher->getFullName(),
                    message:   cutter($e->getMessage(), 150),
                    type:      'error',
                ));
            }

        } finally {

            tenancy()->end();

        }
    }

    /**
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        tenancy()->initialize($this->tenantId);

        try {
            $teacher = Teacher::find($this->teacherId);

            if ($teacher) {
                
                $director = User::first();

                $director?->notify(new RealTimeNotification(
                    userEmail: $director->email,
                    tenantId:  $this->tenantId,
                    title:     "ECHEC DE LA CREATION D'ACCES A L'ENSEIGNANT " . $teacher->getFullName(),
                    message:   cutter($exception->getMessage(), 150),
                    type:      'error',
                ));
            }

        } finally {
            tenancy()->end();
        }
    }
    
}
