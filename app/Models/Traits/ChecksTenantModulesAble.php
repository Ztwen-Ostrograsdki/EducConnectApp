<?php

namespace App\Models\Traits;

/**
 * Helpers *Able() pour vérifier si un module est actif
 * sur une subscription valide.
 *
 * Utilisable sur :
 * - TenantModuleAccess
 * - Tenant (via activeSubscription → moduleAccess)
 *
 * Convention : snake_case du module → camelCase + suffixe "Able"
 * ex. sms_notifications → smsNotificationsAble()
 */
trait ChecksTenantModulesAble
{
    /**
     * Vérifie qu'un module est activé et que la subscription liée est utilisable.
     *
     * Sur Tenant : retourne false s'il n'y a pas d'activeSubscription.
     * Sur TenantModuleAccess : vérifie isEditable() + hasModule().
     */
    public function moduleIsAble(string $module): bool
    {
        // ── Chemin Tenant ─────────────────────────────────────────────
        if ($this instanceof \App\Models\Tenant) {
            $subscription = $this->activeSubscription;

            // Pas de subscription active → false systématique
            if (! $subscription) {
                return false;
            }

            // Subscription marquée active mais date dépassée
            if ($subscription->isExpired()) {
                return false;
            }

            $access = $subscription->moduleAccess
                ?? \App\Models\TenantModuleAccess::query()
                    ->where('subscription_id', $subscription->id)
                    ->first();

            if (! $access) {
                return false;
            }

            return $access->hasModule($module);
        }

        // ── Chemin TenantModuleAccess ─────────────────────────────────
        if (! array_key_exists($module, \App\Models\TenantModuleAccess::moduleLabels())) {
            return false;
        }

        if (method_exists($this, 'isEditable') && ! $this->isEditable()) {
            return false;
        }

        if (! method_exists($this, 'hasModule')) {
            return false;
        }

        return (bool) $this->hasModule($module);
    }

    // ─── Communications ───────────────────────────────────────────────

    public function smsNotificationsAble(): bool
    {
        return $this->moduleIsAble('sms_notifications');
    }

    public function emailNotificationsAble(): bool
    {
        return $this->moduleIsAble('email_notifications');
    }

    public function whatsappNotificationsAble(): bool
    {
        return $this->moduleIsAble('whatsapp_notifications');
    }

    // ─── Bulletins ────────────────────────────────────────────────────

    public function pdfBulletinsAble(): bool
    {
        return $this->moduleIsAble('pdf_bulletins');
    }

    public function bulletinEmailSendAble(): bool
    {
        return $this->moduleIsAble('bulletin_email_send');
    }

    public function bulletinWhatsappSendAble(): bool
    {
        return $this->moduleIsAble('bulletin_whatsapp_send');
    }

    public function bulletinSmsSendAble(): bool
    {
        return $this->moduleIsAble('bulletin_sms_send');
    }

    // ─── Notes & classements ──────────────────────────────────────────

    public function marksManagementAble(): bool
    {
        return $this->moduleIsAble('marks_management');
    }

    public function rankingsAble(): bool
    {
        return $this->moduleIsAble('rankings');
    }

    public function marksReportsAble(): bool
    {
        return $this->moduleIsAble('marks_reports');
    }

    // ─── Impressions & documents ──────────────────────────────────────

    public function customPrintsAble(): bool
    {
        return $this->moduleIsAble('custom_prints');
    }

    public function printableDocsAble(): bool
    {
        return $this->moduleIsAble('printable_docs');
    }

    // ─── Statistiques ─────────────────────────────────────────────────

    public function semesterStatisticsAble(): bool
    {
        return $this->moduleIsAble('semester_statistics');
    }

    public function annualStatisticsAble(): bool
    {
        return $this->moduleIsAble('annual_statistics');
    }

    public function attendanceReportsAble(): bool
    {
        return $this->moduleIsAble('attendance_reports');
    }

    public function paymentReportsAble(): bool
    {
        return $this->moduleIsAble('payment_reports');
    }

    public function performanceReportsAble(): bool
    {
        return $this->moduleIsAble('performance_reports');
    }

    // ─── Paiements ────────────────────────────────────────────────────

    public function onlinePaymentsAble(): bool
    {
        return $this->moduleIsAble('online_payments');
    }

    public function paymentRemindersAble(): bool
    {
        return $this->moduleIsAble('payment_reminders');
    }

    public function paymentReceiptsAble(): bool
    {
        return $this->moduleIsAble('payment_receipts');
    }

    // ─── Import / Export ──────────────────────────────────────────────

    public function excelImportAble(): bool
    {
        return $this->moduleIsAble('excel_import');
    }

    public function excelExportAble(): bool
    {
        return $this->moduleIsAble('excel_export');
    }

    public function pdfExportAble(): bool
    {
        return $this->moduleIsAble('pdf_export');
    }

    // ─── Portails ─────────────────────────────────────────────────────

    public function parentPortalAble(): bool
    {
        return $this->moduleIsAble('parent_portal');
    }

    public function studentPortalAble(): bool
    {
        return $this->moduleIsAble('student_portal');
    }

    public function teacherPortalAble(): bool
    {
        return $this->moduleIsAble('teacher_portal');
    }

    // ─── Avancés ──────────────────────────────────────────────────────

    public function multiPeriodAble(): bool
    {
        return $this->moduleIsAble('multi_period');
    }

    public function timetableAble(): bool
    {
        return $this->moduleIsAble('timetable');
    }

    public function libraryManagementAble(): bool
    {
        return $this->moduleIsAble('library_management');
    }

    public function canteenManagementAble(): bool
    {
        return $this->moduleIsAble('canteen_management');
    }

    public function transportManagementAble(): bool
    {
        return $this->moduleIsAble('transport_management');
    }
}
