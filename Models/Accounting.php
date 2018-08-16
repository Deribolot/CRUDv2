<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:47 AM
 */

namespace CRUD\Models;

/**
 * Class Accounting
 * @package CRUD\Models
 */
class Accounting extends Small
{
    /**
     * @var array
     */
    public const TABLE_PARAMS_DERICTORY = ['idQualificationsAccounting' => 'ID_QUALIFICATIONS_ACCOUNTING', 'fIdQualification' => 'FID_QUALIFICATION', 'fLevelQualificationLevel' => 'FLEVEL_QUALIFICATION_LEVEL', 'fIdProgrammer' => 'FID_PROGRAMMER'];
    /**
     * @var array
     */
    public const FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES = ['fIdQualification' => ['entity' => 'Qualification', 'entityParam' => null],
        'fLevelQualificationLevel' => ['entity' => 'Level', 'entityParam' => null],
        'fIdProgrammer' => ['entity' => 'Programmer', 'entityParam' => null]];

    /**
     * @return string
     */
    public static function getTableName():string
    {
        return 'QUALIFICATIONS_ACCOUNTING';
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
        return 'idQualificationsAccounting';
    }

    /**
     * @return string
     */
    public function getIdQualificationsAccounting():string
    {
        return $this->getParamValue('idQualificationsAccounting');
    }

    /**
     * @param string $value
     */
    public function setIdQualificationsAccounting(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('idQualificationsAccounting', $value);
    }

    /**
     * @return string
     */
    public function getFIdQualification():string
    {
        return $this->getParamValue('fIdQualification');
    }

    /**
     * @param string $value
     */
    public function setFIdQualification(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('fIdQualification', $value);
    }

    /**
     * @return string
     */
    public function getFLevelQualificationLevel():string
    {
        return $this->getParamValue('fLevelQualificationLevel');
    }

    /**
     * @param string $value
     */
    public function setFLevelQualificationLevel(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('fLevelQualificationLevel', $value);
    }

    /**
     * @return string
     */
    public function getFIdProgrammer():string
    {
        return $this->getParamValue('fIdProgrammer');
    }

    /**
     * @param string $value
     */
    public function setFIdProgrammer(string $value)
    {
        $valueIntegerString=get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
        $this->setParamValue('fIdProgrammer', $value);
    }

    /**
     * @return array
     */
    public static function findDirectories(array $params=[]):array
    {
        $levelParams=[];
        $qualificationParams=[];
        $programmerParams=[];
        foreach (array_keys($params) as $paramName){
            foreach (get_called_class()::FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES as $tableData){
                $className="\CRUD\Models\\".$tableData['entity'];
                if (in_array($paramName,array_keys($className::TABLE_PARAMS_DERICTORY))){
                    if (strcasecmp($tableData['entity'],'Programmer')===0){
                        $programmerParams[$paramName]=$params[$paramName];
                    }elseif (strcasecmp($tableData['entity'],'Qualification')===0){
                        $qualificationParams[$paramName]=$params[$paramName];
                    }elseif (strcasecmp($tableData['entity'],'Level')===0){
                        $levelParams[$paramName]=$params[$paramName];
                    }
                }
            }
        }
        $findDerictoriesAll = [];
        $findDerictoriesAll['levels'] = Level::findAll($levelParams);
        $findDerictoriesAll['qualifications'] = Qualification::findAll($qualificationParams);
        $findDerictoriesAll['programmers'] = Programmer::findAll($programmerParams);
        if ($findDerictoriesAll['levels']['statusRequest'] && $findDerictoriesAll['qualifications']['statusRequest'] && $findDerictoriesAll['programmers']['statusRequest']) {
            //если выполнен запрос
            return ['statusRequest' => true, 'dataArray' => ['levels' => $findDerictoriesAll['levels']['dataArray'],
                'programmers' => $findDerictoriesAll['programmers']['dataArray'], 'qualifications' => $findDerictoriesAll['qualifications']['dataArray']], 'errorMessage' => ''];
        } else {
            $errorMessagePieces = [];
            //не выполнен запрос
            foreach ($findDerictoriesAll as $key => $value) {
                if (!$value['statusRequest']) $errorMessagePieces[] = $value['errorMessage'];
            }
            return ['statusRequest' => false, 'dataArray' => [], 'errorMessage' => implode(' ', $errorMessagePieces)];
        }
    }

    /**
     * @return array
     */
    public function checkToAddRow():array
    {
        $returnResult = $this->checkParam(['fIdQualification', 'fIdProgrammer']);
        if ($returnResult['statusFindSavingRowRequest'] && !$returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => true, 'errorMessage' => ''];
        } elseif ($returnResult['statusFindSavingRowRequest'] && $returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. Этот навык этого программиста уже учтен. '];
        } else {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. ' . $returnResult['errorMessage']];
        }
    }

    /**
     * @return array
     */
    public function checkToUpdateRow():array
    {
        $getFindByPKRequest = get_called_class()::findByPK($this->pK);
        if ($getFindByPKRequest['statusRequest']) {
            $params = $this->getParams();
            unset($params[get_class()::getPKName()]);
            unset($params['fLevelQualificationLevel']);
            $returnArray = ['statusСheckSavingRowRequest' => false, 'errorMessage' => ""];
            $requestResult = $this->findAnyByParam($params);
            if ($requestResult['statusRequest']) {
                if (count($requestResult['dataArray']) > 0) {
                    foreach ($requestResult['dataArray'] as $accounting) {
                        if (strcmp($accounting[get_class()::getPKName()], get_class()::getPK()) !== 0) {
                            $returnArray['errorMessage'] = 'Произошла ошибка при проверке на уникальность записи. Этот навык этого программиста уже учтен. ';
                            return $returnArray;
                        }
                    }
                    $requestResult = $this->findAnyByParam($this->getParams());
                    if ($requestResult['statusRequest']) {
                        if (count($requestResult['dataArray']) > 0) {
                            $returnArray['errorMessage'] = 'Произошла ошибка при проверке на уникальность записи. Уровень навыка программиста не изменился, в сохранении отказано. ';
                        } else {
                            $returnArray['statusСheckSavingRowRequest'] = true;
                        }
                    } else {
                        $returnArray['errorMessage'] = $requestResult['errorMessage'];
                    }
                } else {
                    $returnArray['statusСheckSavingRowRequest'] = true;
                }
            } else {
                $returnArray['errorMessage'] = $requestResult['errorMessage'];
            }
            return $returnArray;
        } else {
            return ['statusСheckSavingRowRequest' => $getFindByPKRequest['statusRequest'], 'errorMessage' => 'Произошла ошибка при поиске обновляемой записи. ' . $getFindByPKRequest['errorMessage']];
        }
    }

    /**
     * @return array
     */
    public static function findAll(array $params = []):array
    {
        return get_called_class()::findAnyByParam( $params ,['PROGRAMMER'=>['programmerSurname','programmerName','programmerPatronymic','programmerToken'],'QUALIFICATION'=>['qualificationName']]);
    }
}