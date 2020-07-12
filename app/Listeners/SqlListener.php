<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class SqlListener
{
    public function __construct()
    {
    }

    public function handle(QueryExecuted $event)
    {
        if (app()->environment() !== 'production') {
            $sql = str_replace("?", "'%s'", $event->sql);
            $log = vsprintf($sql, $event->bindings);
            $log = '[' . date('Y-m-d H:i:s') . '] ' . $log . "\r\n";
            Log::channel('debugsql')->debug(TRACE_ID, ['sql'=>$log]);
        }
    }
}
