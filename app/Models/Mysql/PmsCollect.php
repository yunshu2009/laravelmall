<?php

namespace App\Models\Mysql;

class PmsCollect extends BaseModel
{
    protected $table = 'pms_collect';

    protected $fillable = [
        'user_id',
        'value_id'
    ];

    public function goods()
    {
        return $this->belongsTo(PmsGoods::class, 'value_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(SmsTopic::class, 'value_id', 'id');
    }
}
