<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\DataUpdatedEvent;
use App\Events\TeacherWasBlockedEvent;
use App\Models\Classe;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Page profil Enseignant")]
class TeacherProfilPage extends Component
{
    use WireUiActions;
    
    public string $teacher_uuid;

    public function mount(string $teacher_uuid)
    {
        $this->teacher_uuid = $teacher_uuid;
    }

    public function lockTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Bloquer cet enseignant ?',
            'text'               => 'L\'enseignant n\'aura plus accès à son espace.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, bloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherLocking',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherLocking')]
    public function ConfirmTeacherLocking(int $teacherId): void
    {
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        $teacher->update(['blocked' => true]);

        broadcast(new TeacherWasBlockedEvent(tenant('id'), $teacher->id));

        $this->notification()->success(
            title: 'Enseignant bloqué',
            description: "L'enseignant {$teacher->getFullName()} a été bloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function lockAccessToClasse(int $teacherId, int $classeId): void
    {
        $this->dispatch('swal', [
            'title'              => "Bloquer l'accès de cet enseignant à la classe?",
            'text'               =>"L'enseignant n'aura plus accès à cette classe.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Bloquer son accès',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToLockTeacherAccessToClasse',
            'onConfirmedParams'  => ['teacherId' => $teacherId, 'classeId' => $classeId],
        ]);
    }

    #[On('ConfirmToLockTeacherAccessToClasse')]
    public function OnConfirmToLockTeacherAccessToClasse(int $teacherId, int $classeId): void
    {
        $teacher = Teacher::find($teacherId);

        $classe = Classe::find($classeId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        if (!$classe) {
            $this->notification()->error(title: 'Classe introuvable');
            return;
        }

        try {
            if($teacher->canAccessIntoClasse($classe->id)){

                $locked_for_teachers = $classe->locked_for_teachers;

                $locked_for_teachers[$teacherId] = $teacherId;

                $classe->update(['locked_for_teachers' => $locked_for_teachers]);

            }

            $this->notification()->success(
                title: "L'accès de l'enseignant à été bloqué",
                description: "{$teacher->getFullName()} n'a plus accès à la classe " . $classe->name,
            );

            broadcast(new DataUpdatedEvent(tenant('id')));

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "L'accès de l'enseignant {$teacher?->getFullName()} à la classe {$classe->name} n'a pas pu être résolu ",
                description: "Raisons : " . cutter($th->getMessage(), 150),
            );
        }
    }

    public function unLockAccessToClasse(int $teacherId, int $classeId): void
    {
        $this->dispatch('swal', [
            'title'              => "Autoriser l'accès de cet enseignant à la classe?",
            'text'               =>"L'enseignant aura de nouveau accès à cette classe.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Autoriser son accès',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'UnlockAccessToClasse',
            'onConfirmedParams'  => ['teacherId' => $teacherId, 'classeId' => $classeId],
        ]);
    }

    #[On('UnlockAccessToClasse')]
    public function OnUnlockAccessToClasse(int $teacherId, int $classeId): void
    {
        $teacher = Teacher::find($teacherId);

        $classe = Classe::find($classeId);

        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        if (!$classe) {
            $this->notification()->error(title: 'Classe introuvable');
            return;
        }

        try {
            if($teacher->cannotAccessIntoClasse($classe->id)){

                $locked_for_teachers = $classe->locked_for_teachers;

                unset($locked_for_teachers[$teacherId]);

                array_values($locked_for_teachers);

                $classe->update(['locked_for_teachers' => $locked_for_teachers]);

                broadcast(new DataUpdatedEvent(tenant('id')));

                $this->notification()->success(
                    title: "L'accès de l'enseignant à été autorisé",
                    description: "L'enseignant {$teacher->getFullName()} a à présent accès à la classe " . $classe->name,
                );

            }

            

        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "L'accès de l'enseignant {$teacher?->getFullName()} à la classe {$classe->name} n'a pas pu être résolu ",
                description: "Raisons : " . cutter($th->getMessage(), 150),
            );
        }
    }

    public function unlockTeacher(int $teacherId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Débloquer cet enseignant ?',
            'text'               => 'L\'enseignant retrouvera accès à son espace.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, débloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#84cc16',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmTeacherUnLocking',
            'onConfirmedParams'  => ['teacherId' => $teacherId],
        ]);
    }

    #[On('ConfirmTeacherUnLocking')]
    public function OnConfirmTeacherUnLocking(int $teacherId): void
    {
        $teacher = Teacher::find($teacherId);


        if (!$teacher) {
            $this->notification()->error(title: 'Enseignant introuvable');
            return;
        }

        $teacher->update(['blocked' => false]);

        $this->notification()->success(
            title: 'Enseignant débloqué',
            description: "L'enseignant {$teacher->getFullName()} a été débloqué.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function render()
    {
        $teacher = Teacher::withTrashed()->where('uuid', $this->teacher_uuid)->firstOrFail();

        $user = $teacher->user;


        return view('livewire.tenants.teachers.teacher-profil-page', compact('teacher', 'user'));
    }
}
