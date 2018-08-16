<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:46 AM
 */

namespace CRUD\Models;
/**
 * Class Large
 * @package CRUD\Models
 */
abstract class Large extends Small
{
    /**
     * @param string $pK
     * @return array
     */
    abstract public static function findFullDataByPK(string $pK):array;
}