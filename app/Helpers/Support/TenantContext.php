<?php

namespace App\Helpers\Support;


class TenantContext
{
    public static function forJs(): array
    {
        $isTenant = tenant() !== null;

        return [
            'isTenant' => $isTenant,
            'tenantId' => $isTenant ? tenant('id') : null,
            'channel'  => $isTenant
                ? 'tenant.' . tenant('id')
                : 'central-admin',
            'guard'    => $isTenant ? 'tenant' : 'central',
        ];
    }
}