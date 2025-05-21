<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function casts()
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i a',
            'updated_at' => 'datetime:d/m/Y H:i a',
        ];
    }
}
