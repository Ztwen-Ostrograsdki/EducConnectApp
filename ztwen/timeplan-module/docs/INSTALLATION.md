# Module TimePlan — intégration EducConnect

Ce pack est un module d'intégration basé sur les noms et relations observés dans les modèles fournis : `Classe`, `SchoolYear`, `ClasseSubjectOfSchoolYear`, `Subject` et `Teacher`. Il ne remplace pas les fichiers existants du projet.

## 1. Copier les fichiers

Copier les fichiers `app/Models/TimePlan.php`, `app/Models/TimePlanSlot.php`, `app/Livewire/TimePlans/ManageTimePlan.php`, les deux migrations dans le dossier tenant et la vue Blade vers les chemins correspondants de l'application.

Les migrations sont placées dans `database/migrations/tenant`, conformément à une organisation Stancl Tenancy classique. Vérifier que ce chemin est bien celui configuré par le projet pour les migrations des bases tenant.

## 2. Ajouter la route

Le fichier `routes/timeplans.php` est un exemple. Ajouter sa route dans le groupe de routes tenant existant et protéger l'accès avec le middleware/guard qui est déjà utilisé pour les directeurs. Ne pas enregistrer cette route dans le groupe central.

Exemple à adapter dans le fichier de routes tenant :

```php
Route::middleware(['web', 'auth']) // remplacer par le guard/middleware directeur du projet
    ->group(base_path('routes/timeplans.php'));
```

Si l'application utilise un guard explicite ou des middlewares de rôle, les utiliser à la place de `auth`. Le composant vérifie par défaut le rôle Spatie `directeur` via l'utilisateur authentifié. Si EducConnect utilise un guard ou une Policy différente, adapte `authorizeDirectorAccess()` à cette autorisation réelle.

## 3. Lancer les migrations

Exécuter la commande de migration tenant déjà utilisée par EducConnect. Par exemple, dans un environnement Stancl standard, cela peut être :

```bash
php artisan tenants:migrate
```

Vérifier la commande exacte de l'application avant de l'exécuter en production.

## 4. Remplacement automatique d'un enseignant — modification obligatoire

Les créneaux pointent vers `classe_subject_of_school_year_id`. Or la méthode existante `replaceTeacher()` clôture l'ancienne affectation et crée une nouvelle ligne. Pour transférer automatiquement les créneaux, remplacez le corps de la méthode par la version transactionnelle suivante dans `App\Models\ClasseSubjectOfSchoolYear` :

```php
public function replaceTeacher(int $newTeacherId, string $reason, int $replacedBy): static
{
    return \Illuminate\Support\Facades\DB::connection('tenant')->transaction(function () use ($newTeacherId, $reason, $replacedBy) {
        $oldAssignment = static::query()->lockForUpdate()->findOrFail($this->getKey());

        if (!$oldAssignment->isCurrent()) {
            throw new \DomainException('Cette affectation a déjà été clôturée ou remplacée.');
        }

        $newAssignment = static::query()->create([
            'classe_id' => $oldAssignment->classe_id,
            'subject_id' => $oldAssignment->subject_id,
            'school_year_id' => $oldAssignment->school_year_id,
            'teacher_id' => $newTeacherId,
            'coefficient' => $oldAssignment->coefficient,
            'is_active' => true,
            'replacement_reason' => $reason,
            'started_at' => now(),
            'ended_at' => null,
        ]);

        // Re-pointer les créneaux avant de clôturer l'affectation historique.
        \App\Models\TimePlanSlot::query()
            ->where('classe_subject_of_school_year_id', $oldAssignment->id)
            ->update(['classe_subject_of_school_year_id' => $newAssignment->id]);

        $oldAssignment->update([
            'ended_at' => now(),
            'replaced_by' => $replacedBy,
            'is_active' => false,
        ]);

        return $newAssignment;
    });
}
```

Cette opération transfère les créneaux qui pointent vers l'ancienne affectation. Elle ne doit pas être exécutée si l'affectation a déjà été remplacée. Ajouter aussi une validation métier qui garantit que le nouvel enseignant est autorisé à enseigner dans cette classe.

## 5. Points à vérifier dans le projet

- La migration suppose que les clés primaires des tables existantes `classes`, `school_years` et `classe_subject_of_school_years` sont compatibles avec `foreignId()` (unsigned BIGINT).
- Le composant suppose que `Teacher` expose `name` et `prenoms`, conformément au code fourni. Ajuster l'affichage si les noms réels diffèrent.
- Le composant suppose que `SchoolYear` expose `min_year`, `max_year`, `is_active`, `is_closed`, et que `Classe` expose `name`, `school_year_id`, `is_active`.
- La vue utilise les composants Blade Lucide `x-lucide-*`, déjà utilisés dans le projet. Si le paquet n'enregistre pas ces alias, les remplacer par les icônes disponibles.
- Le statut `archived` bloque les modifications depuis l'interface. Les règles d'accès doivent aussi être appliquées côté serveur via la Policy du projet.
- Les validations de conflits sont applicatives. Pour une forte concurrence, envisager un verrouillage transactionnel supplémentaire ou une règle métier dédiée.
- Avant déploiement, vérifier le rendu et exécuter les migrations sur une base tenant de test, puis ajouter des tests Feature pour conflits, publication, clôture d'année et remplacement d'enseignant.
