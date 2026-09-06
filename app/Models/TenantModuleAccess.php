<?php

namespace App\Models;

use App\Models\Traits\ChecksTenantModulesAble;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantModuleAccess extends Model
{
    use ChecksTenantModulesAble;

    protected $connection = 'central';

    protected $table = 'tenant_module_accesses';

    protected $fillable = [
        'tenant_id',
        'subscription_id',
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

        // Notes & classements
        'marks_management',
        'rankings',
        'marks_reports',

        // Impressions & documents
        'custom_prints',
        'printable_docs',

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

        'marks_management' => 'boolean',
        'rankings' => 'boolean',
        'marks_reports' => 'boolean',

        'custom_prints' => 'boolean',
        'printable_docs' => 'boolean',

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
                'marks_management' => true,
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
                'marks_management' => true,
                'rankings' => true,
                'marks_reports' => true,
                'custom_prints' => true,
                'printable_docs' => true,
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
                'marks_management' => true,
                'rankings' => true,
                'marks_reports' => true,
                'custom_prints' => true,
                'printable_docs' => true,
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

            // Notes & classements
            'marks_management' => ['label' => 'Gestion des notes', 'description' => 'Saisie et suivi des notes', 'category' => 'Notes'],
            'rankings' => ['label' => 'Classements', 'description' => 'Meilleurs et plus faibles élèves', 'category' => 'Notes'],
            'marks_reports' => ['label' => 'Rapports de notes', 'description' => 'Rapports des notes renseignées', 'category' => 'Notes'],

            // Impressions & documents
            'custom_prints' => ['label' => 'Impressions personnalisées', 'description' => 'Configuration d’impression personnalisée', 'category' => 'Documents'],
            'printable_docs' => ['label' => 'Fichiers imprimables', 'description' => 'Génération de documents imprimables', 'category' => 'Documents'],

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeByPack(Builder $query, string $pack): Builder
    {
        return $query->where('pack', $pack);
    }

    /**
     * Accès liés à une subscription encore active (non expirée).
     */
    public function scopeWithActiveSubscription(Builder $query): Builder
    {
        return $query->whereHas('subscription', function (Builder $q) {
            $q->where('status', 'active')
                ->where('expire_at', '>', now());
        });
    }

    /**
     * Scope historique : pack non expiré (pack_expires_at).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('pack_expires_at')
                ->orWhere('pack_expires_at', '>', now());
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    public function hasModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }

        return (bool) $this->$module;
    }

    /**
     * Éditable uniquement si la subscription liée est active et non expirée.
     */
    public function isEditable(): bool
    {
        $subscription = $this->subscription;

        if (! $subscription) {
            return false;
        }

        return $subscription->status === 'active'
            && $subscription->expire_at
            && $subscription->expire_at->isFuture();
    }

    public function isValid(): bool
    {
        return ! $this->pack_expires_at || $this->pack_expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->pack_expires_at && $this->pack_expires_at->isPast();
    }

    /**
     * Applique un pack prédéfini (réinitialise puis active les modules du pack).
     */
    public function applyPack(string $packName): void
    {
        $packs = self::packs();

        if (! isset($packs[$packName])) {
            return;
        }

        $allModules = array_keys(self::moduleLabels());
        $reset = array_fill_keys($allModules, false);
        $packModules = $packs[$packName];

        $this->update(array_merge($reset, $packModules, [
            'pack' => $packName,
            'pack_started_at' => now(),
        ]));
    }

    public function enableModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }

        $this->update([$module => true, 'pack' => 'custom']);

        return true;
    }

    public function disableModule(string $module): bool
    {
        if (! isset($this->casts[$module])) {
            return false;
        }

        $this->update([$module => false, 'pack' => 'custom']);

        return true;
    }

    /**
     * @return bool nouvel état du module
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
     * @return array<string>
     */
    public function enabledModules(): array
    {
        $modules = array_keys(self::moduleLabels());

        return array_values(array_filter($modules, fn ($m) => $this->hasModule($m)));
    }

    /**
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
     * Crée un TenantModuleAccess lié à un tenant + une subscription.
     */
    public static function createForSubscription(
        string $tenantId,
        int|string $subscriptionId,
        string $pack = 'starter',
        ?\DateTimeInterface $expiresAt = null
    ): static {
        $packs = self::packs();
        $modules = array_fill_keys(array_keys(self::moduleLabels()), false);
        $packModules = $packs[$pack] ?? [];

        return static::create(array_merge($modules, $packModules, [
            'tenant_id' => $tenantId,
            'subscription_id' => $subscriptionId,
            'pack' => $pack,
            'pack_started_at' => now(),
            'pack_expires_at' => $expiresAt,
        ]));
    }

    /**
     * @deprecated Préférer createForSubscription()
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
