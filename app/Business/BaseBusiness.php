<?php

namespace App\Business;

class BaseBusiness
{
    protected static $select = [];

    public static function setSelect($select)
    {
        self::$select = $select;
    }
}
