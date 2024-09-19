<?php

use App\Models\Tenant;

return [
    'table_name' => 'tenants',
    'relation_table_name' => 'tenant_user',
    'relation_foreign_key' => 'tenant_id',
    'model' => Tenant::class,
];
