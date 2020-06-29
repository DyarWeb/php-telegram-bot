<?php
declare(strict_types=1);

namespace AnarchyService\DB;

/**
 * Class DB
 * @package AnarchyService
 */
class DB
{

    /**
     * @return Json|MongoDB|Mysql
     */
    public static function Database()
    {
        if (getenv('DB' == 'json')) $DB = new Json();
        elseif (getenv('DB' == 'mysql')) $DB = new Mysql();
        elseif (getenv('DB' == 'mongodb')) $DB = new MongoDB();
        return $DB;
    }
}