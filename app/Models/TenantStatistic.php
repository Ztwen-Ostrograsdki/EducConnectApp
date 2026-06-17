<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;


class TenantStatistic extends Model
{
    protected $table = 'tenant_statistics';

    protected $fillable = [
        'tenant_id',
        'students_count',
        'teachers_count',
        'classes_count',
        'parents_count',
        'payments_count',
        'payments_pending',
        'attendance_rate',
        'payment_rate',
        'current_school_year',
        'last_activity_at',
        'last_synced_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'last_synced_at'   => 'datetime',
        'attendance_rate'  => 'decimal:2',
        'payment_rate'     => 'decimal:2',
    ];

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Get the tenant this statistic belongs to.
     *
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Create default statistics for a new tenant.
     *
     * @param string $tenantId
     * @return static
     */
    public static function createForTenant(string $tenantId): static
    {
        return static::create([
            'tenant_id'       => $tenantId,
            'last_synced_at'  => now(),
        ]);
    }

    /**
     * Sync all statistics for a given tenant from its DB.
     *
     * @param Tenant $tenant
     * @return static
     */
    public static function syncForTenant(Tenant $tenant): static
    {
        // Initialiser le contexte tenant
        tenancy()->initialize($tenant);

        $stats = [
            'students_count'       => DB::table('students')->whereNull('deleted_at')->count(),
            'teachers_count'       => DB::table('teachers')->where('status', 'active')->whereNull('deleted_at')->count(),
            'classes_count'        => DB::table('classes')->where('is_active', true)->whereNull('deleted_at')->count(),
            'parents_count'        => DB::table('tutors')->whereNull('deleted_at')->count(),
            'payments_count'       => DB::table('payments')->whereNull('deleted_at')->count(),
            'payments_pending'     => DB::table('payments')->where('status', 'en_attente')->whereNull('deleted_at')->count(),
            'current_school_year'  => DB::table('school_years')->where('is_active', true)->value('slug'),
            'last_synced_at'       => now(),
        ];

        // Calculer les taux
        $totalStudents = $stats['students_count'];
        if ($totalStudents > 0) {
            $presentCount = DB::table('presences')->where('status', 'present')->count();
            $totalPresences = DB::table('presences')->count();
            $stats['attendance_rate'] = $totalPresences > 0
                ? round(($presentCount / $totalPresences) * 100, 2)
                : 0;

            $paidCount = DB::table('payments')->where('status', 'complet')->count();
            $totalPayments = $stats['payments_count'];
            $stats['payment_rate'] = $totalPayments > 0
                ? round(($paidCount / $totalPayments) * 100, 2)
                : 0;
        }

        // Revenir au contexte central
        tenancy()->end();

        // Mettre à jour en DB centrale
        return static::updateOrCreate(
            ['tenant_id' => $tenant->id],
            $stats
        );
    }

    /**
     * Increment a specific counter for a tenant.
     * Called by events — fast, no DB switch needed.
     *
     * @param string $tenantId
     * @param string $column
     * @param int $amount
     * @return void
     */
    public static function increment_column(string $tenantId, string $column, int $amount = 1): void
    {
        static::where('tenant_id', $tenantId)->increment($column, $amount, [
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Decrement a specific counter for a tenant.
     *
     * @param string $tenantId
     * @param string $column
     * @param int $amount
     * @return void
     */
    public static function decrement_column(string $tenantId, string $column, int $amount = 1): void
    {
        static::where('tenant_id', $tenantId)->decrement($column, $amount, [
            'last_activity_at' => now(),
        ]);
    }
}