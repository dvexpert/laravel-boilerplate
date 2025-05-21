<?php

namespace App\Models;

use OwenIt\Auditing\Models\Audit as BaseAudit;
use OwenIt\Auditing\Contracts\Audit as AuditContract;

/**
 * @mixin IdeHelperAudit
 */
class Audit extends BaseAudit implements AuditContract
{
    public function casts(): array
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i a',
            'updated_at' => 'datetime:d/m/Y H:i a',
        ];
    }
}
