<?php

namespace App\Models\Mysql;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mysql\Traits\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    protected $casts = [
        'deleted'   =>  'int',
    ];

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
