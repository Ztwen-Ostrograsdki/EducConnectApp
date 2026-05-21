<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates all roles and permissions for the tenant (school) context.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Permissions ──────────────────────────────────────────────

        $permissions = [

            // ── School Years ──────────────────────────────────────────
            'school_years.view',
            'school_years.create',
            'school_years.edit',
            'school_years.close',

            // ── Classes ───────────────────────────────────────────────
            'classes.view',
            'classes.create',
            'classes.edit',
            'classes.delete',
            'classes.lock',

            // ── Promotions ────────────────────────────────────────────
            'promotions.view',
            'promotions.create',
            'promotions.edit',
            'promotions.delete',

            // ── Filiars ───────────────────────────────────────────────
            'filiars.view',
            'filiars.create',
            'filiars.edit',
            'filiars.delete',

            // ── Serials ───────────────────────────────────────────────
            'serials.view',
            'serials.create',
            'serials.edit',
            'serials.delete',

            // ── Subjects ──────────────────────────────────────────────
            'subjects.view',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',

            // ── Students ──────────────────────────────────────────────
            'students.view',
            'students.view_own',        // élève voit ses propres données
            'students.create',
            'students.edit',
            'students.delete',
            'students.import',          // import Excel
            'students.export',
            'students.view_archives',   // voir les archives d'un élève

            // ── Teachers ──────────────────────────────────────────────
            'teachers.view',
            'teachers.create',
            'teachers.edit',
            'teachers.delete',
            'teachers.invite',          // envoyer lien d'invitation
            'teachers.suspend',         // suspendre l'accès annuel
            'teachers.view_archives',

            // ── Tutors ────────────────────────────────────────────────
            'tutors.view',
            'tutors.create',
            'tutors.edit',
            'tutors.delete',
            'tutors.suspend',
            'tutors.view_own',          // tuteur voit ses enfants

            // ── Marks ─────────────────────────────────────────────────
            'marks.view',
            'marks.view_own',           // élève/tuteur voit ses notes
            'marks.create',
            'marks.edit',
            'marks.edit_locked',        // modifier une note verrouillée (directeur)
            'marks.delete',
            'marks.import',
            'marks.export',
            'marks.send',               // envoyer notes aux parents

            // ── Presences ─────────────────────────────────────────────
            'presences.view',
            'presences.view_own',
            'presences.create',
            'presences.edit',
            'presences.delete',
            'presences.notify_tutor',   // notifier le tuteur

            // ── Payments ──────────────────────────────────────────────
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.export',

            // ── Bulletins ─────────────────────────────────────────────
            'bulletins.view',
            'bulletins.view_own',
            'bulletins.generate',
            'bulletins.send',

            // ── Settings ──────────────────────────────────────────────
            'settings.view',
            'settings.edit',            // modifier les paramètres de l'école
        ];

        // Créer toutes les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── Rôles & attribution des permissions ──────────────────────

        /**
         * Directeur — accès complet à l'école
         */
        $directeur = Role::firstOrCreate(['name' => 'directeur']);
        $directeur->syncPermissions(Permission::all());

        /**
         * Assistant Directeur — accès limité à ses classes et matières
         */
        $enseignant = Role::firstOrCreate(['name' => 'assistant directeur']);
        $enseignant->syncPermissions([
            'classes.view',
            'teachers.view',
            'students.view',
            'students.view_archives',
            'subjects.view',
            'filiars.view',
            'tutors.view',
            'promotions.view',
            'serials.view',
            'marks.view',
            'marks.import',
            'marks.export',
            'presences.view',
            'presences.notify_tutor',
            'bulletins.view',
            'payments.view',
            'bulletins.view',
            'bulletins.generate',
        ]);

        /**
         * Enseignant — accès limité à ses classes et matières
         */
        $enseignant = Role::firstOrCreate(['name' => 'enseignant']);
        $enseignant->syncPermissions([
            'classes.view',
            'students.view',
            'students.view_archives',
            'subjects.view',
            'marks.view',
            'marks.create',
            'marks.edit',
            'marks.import',
            'marks.export',
            'presences.view',
            'presences.create',
            'presences.edit',
            'presences.notify_tutor',
            'bulletins.view',
        ]);

        /**
         * Parent/Tuteur — accès limité à ses enfants
         */
        $tuteur = Role::firstOrCreate(['name' => 'tuteur']);
        $tuteur->syncPermissions([
            'students.view_own',
            'marks.view_own',
            'presences.view_own',
            'bulletins.view_own',
            'payments.view',
            'tutors.view_own',
        ]);

        /**
         * Élève — accès à ses propres données uniquement
         */
        $eleve = Role::firstOrCreate(['name' => 'eleve']);
        $eleve->syncPermissions([
            'students.view_own',
            'marks.view_own',
            'presences.view_own',
            'bulletins.view_own',
        ]);

        $this->command->info('✅ Rôles et permissions créés avec succès !');
        $this->command->table(
            ['Rôle', 'Permissions'],
            [
                ['directeur',  Permission::all()->count().' permissions'],
                ['enseignant', $enseignant->permissions->count().' permissions'],
                ['tuteur',     $tuteur->permissions->count().' permissions'],
                ['eleve',      $eleve->permissions->count().' permissions'],
            ]
        );
    }
}
