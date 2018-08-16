<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:47 AM
 */

namespace CRUD\Models;
/**
 * Class Qualification
 * @package CRUD\Models
 */
class Qualification extends Large
{
    /**
     * @var array
     */
    public const TABLE_PARAMS_DERICTORY = ['idQualification' => 'ID_QUALIFICATION', 'qualificationName' => 'QUALIFICATION_NAME'];

    /**
     * @var array
     */
    public const FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES = [];

    /**
     * @return string
     */
    public static function getTableName():string
    {
        return 'QUALIFICATION';
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
        return 'idQualification';
    }

    /**
     * @return string
     */
    public function getIdQualification():string
    {
        return $this->getParamValue('idQualification');
    }

    /**
     * @return string
     */
    public function getQualificationName():string
    {
        return $this->getParamValue('qualificationName');
    }

    /**
     * @param string $value
     */
    public function setIdQualification(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('idQualification', $value);
    }

    /**
     * @param string $value
     */
    public function setQualificationName(string $value)
    {
        $this->setParamValue('qualificationName', $value);
    }

    /**
     * @param string $pK
     * @return array
     */
    public static function findFullDataByPK(string $pK):array
    {
        $mainRequestResult = get_called_class()::findByPK($pK);
        $beautifulRequestResult = $mainRequestResult;
        if ($beautifulRequestResult['statusRequest']) {
            $beautifulRequestResult['dataArray'] = $mainRequestResult['dataArray'];
            $secondaryRrequestResult = Accounting::findAnyByParam([get_called_class()::getPKName() => $pK],['PROGRAMMER'=>['programmerSurname','programmerName','programmerPatronymic','programmerToken'],'QUALIFICATION_LEVEL'=>['idLevelQualification']],get_called_class()::getTableName());
            if (!$secondaryRrequestResult['statusRequest']) {
                $beautifulRequestResult = $secondaryRrequestResult;
            } else {
                $beautifulRequestResult['dataArray']['fDataArray']['programmers'] = [];
                if (count($secondaryRrequestResult['dataArray']) > 0) {
                    $beautifulRequestResult['dataArray']['fDataArray']['programmers']['dataArray'] = $secondaryRrequestResult['dataArray'];
                } else {
                    $beautifulRequestResult['errorMessage'] = 'Записи о программистах, владеющих этим навыком, отсутствуют.';
                }
            }
        }
        return $beautifulRequestResult;
    }

    /**
     * @return array
     */
    public static function findDirectories():array
    {
        //справочники не нужны
        return ['statusRequest' => true, 'dataArray' => [], 'errorMessage' => ''];
    }

    /**
     * @return array
     */
    public function checkToAddRow():array
    {
        $returnResult = $this->checkParam(['qualificationName']);
        if ($returnResult['statusFindSavingRowRequest'] && !$returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => true, 'errorMessage' => ''];
        } elseif($returnResult['statusFindSavingRowRequest'] && $returnResult['findSavingRowRequestDataExist']){
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. Навык с таким названием уже существует. '];
        } else {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. ' . $returnResult['errorMessage']];
        }
    }

    /**
     * @return array
     */
    public static function findAll(array $params = []):array
    {
        return get_called_class()::findAnyByParam($params,[get_called_class()::getTableName()=>['qualificationName']]);
    }

}