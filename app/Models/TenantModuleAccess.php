<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TenantModuleAccess extends Model
{
    protected $table = 'tenant_module_accesses';

    protected $fillable = [
        'tenant_id',
        'pack',
        'pack_started_at',
        'pack_expires_at',

        // Communications
        'sms_notifications',
        'email_notifications',
        'whatsapp_notifications',

        // Bulletins
        'pdf_bulletins',
        'bulletin_email_send',
        'bulletin_whatsapp_send',
        'bulletin_sms_send',

        // Statistiques
        'semester_statistics',
        'annual_statistics',
        'attendance_reports',
        'payment_reports',
        'performance_reports',

        // Paiements
        'online_payments',
        'payment_reminders',
        'payment_receipts',

        // Import/Export
        'excel_import',
        'excel_export',
        'pdf_export',

        // Portails
        'parent_portal',
        'student_portal',
        'teacher_portal',

        // Avancés
        'multi_period',
        'timetable',
        'library_management',
        'canteen_management',
        'transport_management',

        'notes',
    ];

    protected $casts = [
        'pack_started_at' => 'datetime',
        'pack_expires_at' => 'datetime',

        'sms_notifications' => 'boolean',
        'email_notifications' => 'boolean',
        'whatsapp_notifications' => 'boolean',

        'pdf_bulletins' => 'boolean',
        'bulletin_email_send' => 'boolean',
        'bulletin_whatsapp_send' => 'boolean',
        'bulletin_sms_send' => 'boolean',

        'semester_statistics' => 'boolean',
        'annual_statistics' => 'boolean',
        'attendance_reports' => 'boolean',
        'payment_reports' => 'boolean',
        'performance_reports' => 'boolean',

        'online_payments' => 'boolean',
        'payment_reminders' => 'boolean',
        'payment_receipts' => 'boolean',

        'excel_import' => 'boolean',
        'excel_export' => 'boolean',
        'pdf_export' => 'boolean',

        'parent_portal' => 'boolean',
        'student_portal' => 'boolean',
        'teacher_portal' => 'boolean',

        'multi_period' => 'boolean',
        'timetable' => 'boolean',
        'library_management' => 'boolean',
        'canteen_management' => 'boolean',
        'transport_management' => 'boolean',
    ];

    // ─── Packs prédéfinis ─────────────────────────────────────────────

    /**
     * Définition des modules inclus dans chaque pack.
     *
     * @return array<string, array<string, bool>>
     */
    public static function packs(): array
    {
        return [
            'starter' => [
                'email_notifications' => true,
                'pdf_bulletins' => true,
                'payment_receipts' => true,
                'excel_import' => true,
                'parent_portal' => true,
                'teacher_portal' => true,
            ],

            'pro' => [
                'email_notifications' => true,
                'whatsapp_notifications' => true,
                'pdf_bulletins' => true,
                'bulletin_email_send' => true,
                'bulletin_whatsapp_send' => true,
                'semester_statistics' => true,
                'attendance_reports' => true,
                'payment_reports' => true,
                'payment_receipts' => true,
                'payment_reminders' => true,
                'excel_import' => true,
                'excel_export' => true,
                'pdf_export' => true,
                'parent_portal' => true,
                'student_portal' => true,
                'teacher_portal' => true,
                'multi_period' => true,
                'timetable' => true,
            ],

            'premium' => [
                'sms_notifications' => true,
                'email_notifications' => true,
                'whatsapp_notifications' => true,
                'pdf_bulletins' => true,
                'bulletin_email_send' => true,
                'bulletin_whatsapp_send' => true,
                'bulletin_sms_send' => true,
                'semester_statistics' => true,
                'annual_statistics' => true,
                'attendance_reports' => true,
                'payment_reports' => true,
                'performance_reports' => true,
                'online_payments' => true,
                'payment_reminders' => true,
                'payment_receipts' => true,
                'excel_import' => true,
                'excel_export' => true,
                'pdf_export' => true,
                'parent_portal' => true,
                'student_portal' => true,
                'teacher_portal' => true,
                'multi_period' => true,
                'timetable' => true,
                'library_management' => true,
                'canteen_management' => true,
                'transport_management' => true,
            ],

            'custom' => [], // modules définis manuellement
        ];
    }

    /**
     * Labels lisibles des modules.
     *
     * @return array<string, array{label: string, description: string, category: string}>
     */
    public static function moduleLabels(): array
    {
        return [
            // Communications
            'sms_notifications' => ['label' => 'SMS', 'description' => 'Envoi de notifications par SMS', 'category' => 'Communications'],
            'email_notifications' => ['label' => 'Email', 'description' => 'Envoi de notifications par email', 'category' => 'Communications'],
            'whatsapp_notifications' => ['label' => 'WhatsApp', 'description' => 'Envoi de notifications WhatsApp', 'category' => 'Communications'],

            // Bulletins
            'pdf_bulletins' => ['label' => 'Bulletins PDF', 'description' => 'Génération de bulletins en PDF', 'category' => 'Bulletins'],
            'bulletin_email_send' => ['label' => 'Bulletins par email', 'description' => 'Envoi des bulletins par email', 'category' => 'Bulletins'],
            'bulletin_whatsapp_send' => ['label' => 'Bulletins WhatsApp', 'description' => 'Envoi des bulletins par WhatsApp', 'category' => 'Bulletins'],
            'bulletin_sms_send' => ['label' => 'Bulletins par SMS', 'description' => 'Envoi des bulletins par SMS', 'category' => 'Bulletins'],

            // Statistiques
            'semester_statistics' => ['label' => 'Stats semestrielles', 'description' => 'Statistiques par semestre/trimestre', 'category' => 'Statistiques'],
            'annual_statistics' => ['label' => 'Stats annuelles', 'description' => 'Bilan statistique annuel complet', 'category' => 'Statistiques'],
            'attendance_reports' => ['label' => 'Rapports présences', 'description' => 'Rapports détaillés des présences', 'category' => 'Statistiques'],
            'payment_reports' => ['label' => 'Rapports paiements', 'description' => 'Rapports financiers et paiements', 'category' => 'Statistiques'],
            'performance_reports' => ['label' => 'Rapports performances', 'description' => 'Analyse des performances scolaires', 'category' => 'Statistiques'],

            // Paiements
            'online_payments' => ['label' => 'Paiements en ligne', 'description' => 'Accepter les paiements en ligne', 'category' => 'Paiements'],
            'payment_reminders' => ['label' => 'Rappels paiements', 'description' => 'Rappels automatiques de paiement', 'category' => 'Paiements'],
            'payment_receipts' => ['label' => 'Reçus de paiement', 'description' => 'Génération de reçus PDF', 'category' => 'Paiements'],

            // Import/Export
            'excel_import' => ['label' => 'Import Excel', 'description' => 'Import élèves/notes via Excel', 'category' => 'Import/Export'],
            'excel_export' => ['label' => 'Export Excel', 'description' => 'Export des données en Excel', 'category' => 'Import/Export'],
            'pdf_export' => ['label' => 'Export PDF', 'description' => 'Export des rapports en PDF', 'category' => 'Import/Export'],

            // Portails
            'parent_portal' => ['label' => 'Portail parents', 'description' => 'Accès parents aux données', 'category' => 'Portails'],
            'student_portal' => ['label' => 'Portail élèves', 'description' => 'Accès élèves à leurs résultats', 'category' => 'Portails'],
            'teacher_portal' => ['label' => 'Portail enseignants', 'description' => 'Accès enseignants à leurs classes', 'category' => 'Portails'],

            // Avancés
            'multi_period' => ['label' => 'Multi-période', 'description' => 'Semestres et trimestres', 'category' => 'Avancés'],
            'timetable' => ['label' => 'Emploi du temps', 'description' => 'Gestion des emplois du temps', 'category' => 'Avancés'],
            'library_management' => ['label' => 'Bibliothèque', 'description' => 'Gestion de la bibliothèque', 'category' => 'Avancés'],
            'canteen_management' => ['label' => 'Cantine', 'description' => 'Gestion de la cantine scolaire', 'category' => 'Avancés'],
            'transport_management' => ['label' => 'Transport', 'description' => 'Gestion du transport scolaire', 'category' => 'Avancés'],
        ];
    }

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the tenant this module access belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to filter by pack.
     */
    public function scopeByPack(Builder $query, string $pack): Builder
    {
        return $query->where('pack', $pack);
    }

    /**
     * Scope to get only active (non-expired) module accesses.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('pack_expires_at')
                ->orWhere('pack_expires_at', '>', now());
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Check if a specific module is enabled.
     */
    public function hasModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }

        return (bool) $this->$module;
    }

    /**
     * Check if the pack is still valid (not expired).
     */
    public function isValid(): bool
    {
        return ! $this->pack_expires_at || $this->pack_expires_at->isFuture();
    }

    /**
     * Check if the pack is expired.
     */
    public function isExpired(): bool
    {
        return $this->pack_expires_at && $this->pack_expires_at->isPast();
    }

    /**
     * Apply a predefined pack to this tenant.
     * Resets all modules then applies pack defaults.
     */
    public function applyPack(string $packName): void
    {
        $packs = self::packs();

        if (! isset($packs[$packName])) {
            return;
        }

        // Reset all modules to false
        $allModules = array_keys(self::moduleLabels());
        $reset = array_fill_keys($allModules, false);

        // Apply pack modules
        $packModules = $packs[$packName];

        $this->update(array_merge($reset, $packModules, [
            'pack' => $packName,
            'pack_started_at' => now(),
        ]));
    }

    /**
     * Enable a specific module.
     */
    public function enableModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }
        $this->update([$module => true, 'pack' => 'custom']);

        return true;
    }

    /**
     * Disable a specific module.
     */
    public function disableModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }
        $this->update([$module => false, 'pack' => 'custom']);

        return true;
    }

    /**
     * Toggle a specific module on/off.
     *
     * @return bool — new state
     */
    public function toggleModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }
        $newState = ! $this->$module;
        $this->update([$module => $newState, 'pack' => 'custom']);

        return $newState;
    }

    /**
     * Get all enabled modules.
     *
     * @return array<string>
     */
    public function enabledModules(): array
    {
        $modules = array_keys(self::moduleLabels());

        return array_filter($modules, fn ($m) => $this->hasModule($m));
    }

    /**
     * Get modules grouped by category.
     *
     * @return array<string, array>
     */
    public function modulesByCategory(): array
    {
        $labels = self::moduleLabels();
        $result = [];

        foreach ($labels as $key => $info) {
            $result[$info['category']][] = [
                'key' => $key,
                'label' => $info['label'],
                'description' => $info['description'],
                'enabled' => $this->hasModule($key),
            ];
        }

        return $result;
    }

    /**
     * Create a default starter module access for a new tenant.
     */
    public static function createForTenant(string $tenantId, string $pack = 'starter'): static
    {
        $packs = self::packs();
        $modules = array_fill_keys(array_keys(self::moduleLabels()), false);
        $packModules = $packs[$pack] ?? [];

        return static::create(array_merge($modules, $packModules, [
            'tenant_id' => $tenantId,
            'pack' => $pack,
            'pack_started_at' => now(),
        ]));
    }
}
