<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:46 AM
 */

namespace CRUD\Models;
/**
 * Class Basic
 * @package CRUD\Models
 */
abstract class Basic
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @return string
     */
    abstract public static function getTablePKName(): string;

    /**
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * @return string
     */
    abstract public static function getPKName(): string;

    /**
     * @return string
     */
    public function getPK(): string
    {
        $PKName = get_called_class()::getPKName();
        $metod = 'get' . $PKName;
        return get_called_class()::$metod();
    }

    /**
     * @param string $value
     */
    public function setPK(string $value)
    {
        $PKName = get_called_class()::getPKName();
        $metod = 'set' . $PKName;
        get_called_class()::$metod($value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $sFuncName = 'get' . ucfirst($name);
        if (method_exists($this, $sFuncName)) {
            return $this->$sFuncName();
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set(string $name, string $value)
    {
        $sFuncName = 'set' . ucfirst($name);
        if (method_exists($this, $sFuncName)) {
            $this->$sFuncName($value);
        }
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params ?? [];
    }

    /**
     * @param string $param
     * @return string
     */
    protected function getParamValue(string $param): string
    {
        return isset($this->getParams()[lcfirst($param)]) ? $this->getParams()[lcfirst($param)] : null;
    }

    /**
     * @param string $param
     * @param string $value
     */
    protected function setParamValue(string $param, string $value)
    {
        $this->params[lcfirst($param)] = $value;
    }

    /**
     * @param string $numberString
     * @return string
     */
    public static function getStringIntegerNumber(string $numberString): string
    {
        $integerNumber = "";
        $matchesArray = [];
        if ((strcasecmp(gettype($numberString), 'string') === 0) && (preg_match("~\A[1-9]{1}\d{0,5}\Z~ui", $numberString, $matchesArray))) {
            $integerNumber = $matchesArray[0];
        }
        return $integerNumber;
    }

    /**
     * @return array
     */
    public static function getJoiningRequestPart(): array
    {
        $paramsNamesDerictories = [];
        $requestPart = '';
        $foreignKeysTablesParamsDerictiries = get_called_class()::FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES;
        $mainTableName = get_called_class()::getTableName();
        $mainTableParamsDerictory = get_called_class()::TABLE_PARAMS_DERICTORY;
        $paramsNamesDerictories[] = $mainTableParamsDerictory;
        foreach ($foreignKeysTablesParamsDerictiries as $fKeys => $entityData) {
            $mainEntityParam = $fKeys;
            $foreignEntity = 'CRUD\\Models\\' . $entityData['entity'];
            $foreignTableName = $foreignEntity::getTableName();
            $foreignTableParamsDerictory = $foreignEntity::TABLE_PARAMS_DERICTORY;
            $foreignEntityParam = $entityData['entityParam'] ?? $foreignEntity::getPKName();
            $requestPart .= "LEFT JOIN {$foreignTableName} ON {$mainTableName}.{$mainTableParamsDerictory[$mainEntityParam]}={$foreignTableName}.{$foreignTableParamsDerictory[$foreignEntityParam]} ";
            $recursionJoining = $foreignEntity::getJoiningRequestPart($entityData['entity']);
            $requestPart .= $recursionJoining['requestPart'];
            $paramsNamesDerictories[] = $recursionJoining['paramsNamesDerictory'];
        }
        return ['requestPart' => $requestPart, 'paramsNamesDerictory' => array_merge(...$paramsNamesDerictories)];
    }

    /**
     * @param array $params
     * @param array $allParamsNamesDerictory
     * @return array
     */
    public static function getSelectedRequestPart(array $params, array $allParamsNamesDerictory): array
    {
        $newQueryPrepareParams = [];
        $paramValues = [];
        foreach ($params as $tableName => $tableParams) {
            foreach ($tableParams as $paramName => $paramValue) {
                if (in_array($paramName, array_keys($allParamsNamesDerictory))) {
                    $tableParamName = $allParamsNamesDerictory[$paramName];
                    if (isset($params[$tableName][$paramName]) && (strcmp(gettype($params[$tableName][$paramName]), 'string') !== 0 || strlen($params[$tableName][$paramName]) > 0)) {
                        $newQueryPrepareParams[] = " {$tableName}.{$tableParamName}=? ";
                        $paramValues[] = $paramValue;
                    } else {
                        $newQueryPrepareParams[] = " {$tableName}.{$tableParamName} IS NULL ";
                    }


                }
            }
        }
        return ['paramValues' => $paramValues, 'newQueryPrepareParams' => $newQueryPrepareParams];
    }

    public static function getUpdatedValuesRequestPart(array $params, array $allParamsNamesDerictory): array
    {
        $newQueryPrepareParams = [];
        $paramValues = [];
        foreach ($params as $tableName => $tableParams) {
            foreach ($tableParams as $paramName => $paramValue) {
                if (in_array($paramName, array_keys($allParamsNamesDerictory))) {
                    $tableParamName = $allParamsNamesDerictory[$paramName];
                    $newQueryPrepareParams[] = " {$tableName}.{$tableParamName}=? ";
                    $paramValues[] = $paramValue;
                }
            }
        }
        return ['paramValues' => $paramValues, 'newQueryPrepareParams' => $newQueryPrepareParams];
    }

    /**
     * @param array $params
     * @param array $allParamsNamesDerictory
     * @return array
     */
    public static function getOrderedRequestPart(array $params, array $allParamsNamesDerictory): array
    {
        $paramsNames = [];
        foreach ($params as $tableName => $tableParams) {
            foreach ($tableParams as $paramName) {
                if (in_array($paramName, array_keys($allParamsNamesDerictory))) {
                    $tableParamName = $allParamsNamesDerictory[$paramName];
                    $paramsNames[] = " {$tableName}.{$tableParamName} ";
                }
            }
        }
        return $paramsNames;
    }

    /**
     * @return array
     */
    public static function findAll(): array
    {
        return get_called_class()::findAnyByParam();
    }

    /**
     * @param string $pK
     * @return array
     */
    public static function findByPK(string $pK): array
    {
        $requestResult = get_called_class()::findAnyByParam([get_called_class()::getPKName() => $pK]);
        if ($requestResult['statusRequest']) {
            if (count($requestResult['dataArray']) > 0) {
                $requestResult['dataArray'] = $requestResult['dataArray'][0];
            } else {
                $requestResult['errorMessage'] = ' Запись не найдена. ';
                $requestResult['statusRequest'] = false;
            }
        }
        return $requestResult;
    }

    /**
     * @param array $params
     * @param array $orderedParams
     * @param string $paramsTableName
     * @return array
     */
    protected static function findAnyByParam(array $params = [], array $orderedParams = [], string $paramsTableName = ''): array
    {
        $returnArray = ['statusRequest' => false, 'dataArray' => [], 'errorMessage' => ''];
        global $db;
        $table = get_called_class()::getTableName();
        if ($db) {
            $newQueryPrepare = "SELECT * FROM {$table} ";
            $joiningConstructor = get_called_class()::getJoiningRequestPart();
            $newQueryPrepare .= $joiningConstructor['requestPart'];
            $allParamsNamesDerictory = array_merge($joiningConstructor['paramsNamesDerictory'], get_called_class()::TABLE_PARAMS_DERICTORY);
            $wherePartConstructior = get_called_class()::getSelectedRequestPart([(empty($paramsTableName)) ? get_called_class()::getTableName() : $paramsTableName => $params], $allParamsNamesDerictory);
            $newQueryPrepareParams = $wherePartConstructior['newQueryPrepareParams'];
            $paramValues = $wherePartConstructior['paramValues'];
            $paramsCount = count($params);
            foreach ($params as $item => $ivalue) {
                if (!(isset($params[$item]) && (strcmp(gettype($params[$item]), 'string') !== 0 || strlen($params[$item]) > 0)))
                    $paramsCount -= 1;
            }
            if (!empty($params)) {
                if (count($newQueryPrepareParams) !== count($params)) {
                    $returnArray['errorMessage'] = " Соответствующее одному из полученных параметров поиска поле в таблице БД не найдено.";
                    return $returnArray;
                } elseif (count($paramValues) !== $paramsCount) {
                    $returnArray['errorMessage'] = " Значение одного из полученных параметров поиска не является строкой, содержащей целое положительное чило, не превышающее 999999. Это условие было нарушено. Исправьте значение параметра поиска и повторите запрос.";
                    return $returnArray;
                } else {
                    $newQueryPrepare .= " WHERE";
                    $newQueryPrepare .= implode(' AND ', $newQueryPrepareParams);
                    if (!empty($orderedParams)) {
                        $newQueryPrepare .= " ORDER BY ";
                        $newQueryPrepare .= implode(' , ', get_called_class()::getOrderedRequestPart($orderedParams, $allParamsNamesDerictory));
                    }
                    $query = $db->prepare($newQueryPrepare);
                    $query->execute($paramValues);
                }
            } else {
                if (!empty($orderedParams)) {
                    $newQueryPrepare .= " ORDER BY ";
                    $newQueryPrepare .= implode(' , ', get_called_class()::getOrderedRequestPart($orderedParams, $allParamsNamesDerictory));
                }
                $query = $db->prepare($newQueryPrepare);
                $query->execute();
            }
            if ($db->errorInfo()[2] !== null) {
                $returnArray['errorMessage'] = $db->errorInfo()[2];
                return $returnArray;
            } else {
                $resultDataArray = [];
                foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $value) {
                    $resultDataElement = [];
                    foreach ($value as $tableParam => $tableParamValue) {
                        $param = array_search($tableParam, get_called_class()::TABLE_PARAMS_DERICTORY);
                        if ($param !== false) {
                            $resultDataElement[$param] = $tableParamValue;
                        } else {
                            $param = array_search($tableParam, $joiningConstructor['paramsNamesDerictory']);
                            if ($param !== false) {
                                $resultDataElement[$param] = $tableParamValue;
                            }
                        }
                    }
                    $resultDataArray[] = $resultDataElement;
                }
                $returnArray['dataArray'] = $resultDataArray;
                $returnArray['statusRequest'] = true;
                return $returnArray;
            }
        } else {
            $returnArray['errorMessage'] = " Отсутствует подключение к базе данных.";
            return $returnArray;
        }

    }

    /**
     * @param array $entityArray
     * @return array
     */
    public static function parseFK(array $entityArray): array
    {
        $returnArray = [];
        foreach (get_called_class()::FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES as $fK => $fTable) {
            if (in_array($fTable['entity'], array_keys($entityArray))) {
                $value = get_called_class()::getStringIntegerNumber($entityArray[$fTable['entity']]);
                if (!empty($value)) $returnArray[$fK] = $value;
            }
        }
        return $returnArray;
    }
}