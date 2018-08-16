<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:47 AM
 */

namespace CRUD\Models;
/**
 * Class Level
 * @package CRUD\Models
 */
class Level extends Basic
{
    /**
     * @var array
     */
    public const TABLE_PARAMS_DERICTORY = ['levelQualificationLevel' => 'LEVEL_QUALIFICATION_LEVEL', 'qualificationLevelName' => 'QUALIFICATION_LEVEL_NAME'];

    /**
     * @var array
     */
    public const FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES =[];

    /**
     * @return string
     */
    public static function getTableName():string
    {
        return 'D_QUALIFICATION_LEVEL';
    }

    /**
     * @return string
     */
    public static function getTablePKName():string
    {
        return get_called_class()::TABLE_PARAMS_DERICTORY[get_called_class()::getPKName()];
    }

    /**
     * @return string
     */
    public static function getPKName():string
    {
        return 'levelQualificationLevel';
    }

    /**
     * @return string
     */
    public function getLevelQualificationLevel():string
    {
        return $this->getParamValue('levelQualificationLevel');
    }

    /**
     * @return string
     */
    public function getQualificationLevelName():string
    {
        return $this->getParamValue('qualificationLevelName');
    }

    /**
     * @param string $value
     */
    public function setLevelQualificationLevel(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('levelQualificationLevel', $value);
    }

    /**
     * @param string $value
     */
    public function setQualificationLevelName(string $value)
    {
        $this->setParamValue('qualificationLevelName', $value);
    }
}