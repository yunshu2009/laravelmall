<?php

namespace App\Models\Mysql;

class PmsComment extends BaseModel
{
    protected $table = 'pms_comment';

    public function user()
    {
        return $this->belongsTo(UmsMember::class, 'user_id', 'id');
    }
}
