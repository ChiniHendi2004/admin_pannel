<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TenantHelper
{
    public static function getTenantData()
    {
        $tenantId = Auth::user()->id ; // Default for testing

        $tenant = DB::table('tenants')
            ->where('id', $tenantId)
            ->first();

        if (!$tenant) {
            throw new \Exception('Tenant not found');
        }

        return [
            'tenant_id' => $tenant->id,
            'client_slug' => $tenant->client_slug,
            'website_url' => $tenant->website_url,
            'employee_id' => $tenant->employee_id,
            'academic_session' => $tenant->academic_session,
            'expiration_date' => now()->addYear(),
        ];
    }
}
