<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:47 AM
 */

namespace CRUD\Models;

/**
 * Class Department
 * @package CRUD\Models
 */
class Department extends Large
{
    /**
     * @var array
     */
    public const TABLE_PARAMS_DERICTORY = ['idDepartment' => 'ID_DEPARTMENT', 'departmentName' => 'DEPARTMENT_NAME'];

    /**
     * @var array
     */
    public const FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES = [];

    /**
     * @return string
     */
    public static function getTableName():string
    {
        return 'D_DEPARTMENT';
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
        return 'idDepartment';
    }

    /**
     * @return string
     */
    public function getIdDepartment():string
    {
        return $this->getParamValue('idDepartment');
    }

    /**
     * @return string
     */
    public function getDepartmentName():string
    {
        return $this->getParamValue('departmentName');
    }

    /**
     * @param string $value
     */
    public function setIdDepartment(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('idDepartment', $value);
    }

    /**
     * @param string $value
     */
    public function setDepartmentName(string $value)
    {
        $this->setParamValue('departmentName', $value);
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
            $secondaryRrequestResult = Accounting::findAnyByParam( [get_called_class()::getPKName() => $pK],['PROGRAMMER'=>['programmerSurname','programmerName','programmerPatronymic','programmerToken'],'QUALIFICATION'=>['qualificationName']],get_called_class()::getTableName());
            if (!$secondaryRrequestResult['statusRequest']) {
                $beautifulRequestResult = $secondaryRrequestResult;
            } else {
                $beautifulRequestResult['dataArray']['fDataArray']['programmers'] = [];
                if (count($secondaryRrequestResult['dataArray']) > 0) {
                    $beautifulRequestResult['dataArray']['fDataArray']['programmers']['dataArray'] = $secondaryRrequestResult['dataArray'];
                } else {
                    $beautifulRequestResult['errorMessage'] = 'Записи о программистах, числящихся в данном отделе, отсутствуют.';
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
        $returnResult = $this->checkParam(['departmentName']);
        if ($returnResult['statusFindSavingRowRequest'] && !$returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => true, 'errorMessage' => ''];
        } elseif($returnResult['statusFindSavingRowRequest'] && $returnResult['findSavingRowRequestDataExist']){
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. Отдел с таким названием уже существует. '];
        } else {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. ' . $returnResult['errorMessage']];
        }
    }

    /**
     * @return array
     */
    public static function findAll(array $params = []):array
    {
        return get_called_class()::findAnyByParam($params,[get_called_class()::getTableName()=>['departmentName']]);
    }


}