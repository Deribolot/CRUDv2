<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:46 AM
 */

namespace CRUD\Models;

/**
 * Class Small
 * @package CRUD\Models
 */
abstract class Small extends Basic
{
    /**
     * @return array
     */
    abstract public static function findDirectories():array;

    /**
     * @return array
     */
    abstract public function checkToAddRow():array ;

    /**
     * @param array $params
     * @return array
     */
    protected static function deleteByParam(array $params = []):array
    {
        $returnArray = ['statusRequest' => false, 'errorMessage' => ''];
        global $db;
        $table = get_called_class()::getTableName();
        if ($db) {
            $newQueryPrepare = "DELETE FROM {$table} ";

            $allParamsNamesDerictory = get_called_class()::TABLE_PARAMS_DERICTORY;
            $wherePartConstructior = get_called_class()::getSelectedRequestPart($params, $allParamsNamesDerictory);
            $newQueryPrepareParams = $wherePartConstructior['newQueryPrepareParams'];
            $paramValues = $wherePartConstructior['paramValues'];
            if (!empty($params)) {
                if (count($newQueryPrepareParams) !== count($params)) {
                    $returnArray['errorMessage'] = " Соответствующее одному из полученных параметров поиска поле в таблице БД не найдено.";
                    return $returnArray;
                } elseif (count($paramValues) !== count($params)) {
                    $returnArray['errorMessage'] = " Значение одного из полученных параметров поиска не является строкой, содержащей целое положительное чило, не превышающее 999999. Это условие было нарушено. Исправьте значение параметра поиска и повторите запрос.";
                    return $returnArray;
                } else {
                    $newQueryPrepare .= " WHERE";
                    $newQueryPrepare .= implode(' AND ', $newQueryPrepareParams);
                    $query = $db->prepare($newQueryPrepare);
                    $query->execute($paramValues);
                }
            } else {
                $query = $db->prepare($newQueryPrepare);
                $query->execute();
            }
            if ($db->errorInfo()[2] !== null) {
                $returnArray['errorMessage'] = $db->errorInfo()[2];
                return $returnArray;
            } else {
                $returnArray['statusRequest'] = true;
                return $returnArray;
            }
        } else {
            $returnArray['errorMessage'] = " Отсутствует подключение к базе данных.";
            return $returnArray;
        }
    }

    /**
     * @param string $pK
     * @return array
     */
    public static function deleteByPK(string $pK):array
    {
        $getFindByPKRequest = get_called_class()::findByPK($pK);
        if ($getFindByPKRequest['statusRequest']) {
            $getDeleteByPKRequest = get_called_class()::deleteByParam([get_called_class()::getTableName() => [get_called_class()::getPKName() => $pK]]);
            if ($getDeleteByPKRequest['statusRequest']) {
                return ['statusFindByPKRequest' => $getFindByPKRequest['statusRequest'], 'statusDeleteByPKRequest' => $getDeleteByPKRequest['statusRequest'], 'errorMessage' => 'Запись успешно удалена.'];
            } else {
                return ['statusFindByPKRequest' => $getFindByPKRequest['statusRequest'], 'statusDeleteByPKRequest' => $getDeleteByPKRequest['statusRequest'], 'errorMessage' => 'Произошла ошибка при удалении записи. ' . $getDeleteByPKRequest['errorMessage']];
            }
        } else {
            return ['statusFindByPKRequest' => $getFindByPKRequest['statusRequest'], 'statusDeleteByPKRequest' => false, 'errorMessage' => 'Произошла ошибка при поиске записи на удаление. ' . $getFindByPKRequest['errorMessage']];
        }
    }

    /**
     * @param array $post
     * @return array
     */
    public function fillAddData(array $post):array
    {
        $PKName = $this->getPKName();
        if (in_array($PKName, array_keys($post)) && !(empty($post[$PKName]))) {
            return ['isFull' => false, 'errorMessage' => ' При сохранении был передан id, которого не должно существовать у еще несохраненной записи. Этой записи в сохранении отказано. '];
        } else {
            $requiredParams = get_called_class()::TABLE_PARAMS_DERICTORY;
            unset($requiredParams[$PKName]);
            foreach ($requiredParams as $param => $value) {
                if (!in_array($param, array_keys($post)) || empty($post[$param])) {
                    return ['isFull' => false, 'errorMessage' => ' Данные о некоторых обязательных для заполнения полях формы отсутствуют.'];
                } else {
                    $this->$param = $post[$param];
                }
            }
            return ['isFull' => true, 'errorMessage' => ''];
        }
    }

    /**
     * @param array $post
     * @return array
     */
    public function fillUpdateData(array $post):array
    {
        $requiredParams = get_called_class()::TABLE_PARAMS_DERICTORY;
        foreach ($requiredParams as $param => $value) {
            if (!in_array($param, array_keys($post))) {
                return ['isFull' => false, 'errorMessage' => ' Данные о некоторых обязательных для заполнения полях формы отсутствуют.'];
            } else {
                $this->$param = $post[$param];
            }
        }
        return ['isFull' => true, 'errorMessage' => ''];
    }

    /**
     * @param array $params
     * @return array
     */
    protected function checkParam(array $params):array
    {
        $returnResult = ['statusFindSavingRowRequest' => false, 'findSavingRowRequestDataExist' => false, 'errorMessage' => ''];
        $arrayParams = [];
        foreach ($params as $param) {
            $arrayParams[$param] = $this->$param;
        }
        $requestResult = $this->findAnyByParam($arrayParams);
        if ($requestResult['statusRequest']) {
            $returnResult['statusFindSavingRowRequest'] = true;
            if (count($requestResult['dataArray']) > 0) {
                $returnResult['findSavingRowRequestDataExist'] = true;
                $returnResult['errorMessage'] = ' Поля записи, которые должны быть уникальны, не  являются такими. ';
            }
        } else {
            $returnResult['errorMessage'] = $requestResult['errorMessage'];
        }
        return $returnResult;
    }

    /**
     * @return array
     */
    public function checkToUpdateRow():array
    {
        $getFindByPKRequest = get_called_class()::findByPK($this->pK);
        if ($getFindByPKRequest['statusRequest']) {
            return $this->checkToAddRow();
        } else {
            return ['statusСheckSavingRowRequest' => $getFindByPKRequest['statusRequest'], 'errorMessage' => 'Произошла ошибка при поиске обновляемой записи в бд. ' . $getFindByPKRequest['errorMessage']];
        }
    }

    /**
     * @return array
     */
    public function updateRow():array
    {
        {
            $returnArray = ['statusRequest' => false, 'errorMessage' => ''];
            global $db;
            $table = get_called_class()::getTableName();
            if ($db) {
                $newQueryPrepare = "UPDATE {$table} SET ";
                $setPartConstruct = get_called_class()::getUpdatedValuesRequestPart([get_called_class()::getTableName() => $this->getParams()], get_called_class()::TABLE_PARAMS_DERICTORY);
                $newQueryPrepareParams = $setPartConstruct['newQueryPrepareParams'];
                $paramValues = $setPartConstruct['paramValues'];
                $newQueryPrepare .= implode(' , ', $newQueryPrepareParams);
                $newQueryPrepare .= " WHERE ";
                $wherePartConstruct = get_called_class()::getUpdatedValuesRequestPart([get_called_class()::getTableName() => [get_called_class()::getPKName() => $this->pK]], get_called_class()::TABLE_PARAMS_DERICTORY);
                $newQueryPrepareParams2 = $wherePartConstruct['newQueryPrepareParams'];
                $paramValues2 = $wherePartConstruct['paramValues'];
                $newQueryPrepare .= implode(' AND ', $newQueryPrepareParams2);
                $query = $db->prepare($newQueryPrepare);
                $query->execute(array_merge($paramValues, $paramValues2));
                if ($db->errorInfo()[2] !== null) {
                    $returnArray['errorMessage'] = $db->errorInfo()[2];
                    return $returnArray;
                } else {
                    $returnArray['statusRequest'] = true;
                    return $returnArray;
                }
            } else {
                $returnArray['errorMessage'] = " Отсутствует подключение к базе данных.";
                return $returnArray;
            }
        }
    }

    /**
     * @return array
     */
    public function addRow():array
    {
        $returnArray = ['statusRequest' => false, 'errorMessage' => '', 'addingModelPK' => ''];
        global $db;
        $table = get_called_class()::getTableName();
        if ($db) {
            $newQueryPrepare = "INSERT INTO {$table} (";
            $prepareParamsArray = [];
            $signsArray = [];
            foreach (get_called_class():: TABLE_PARAMS_DERICTORY as $paramName => $paramValue)
                if (in_array($paramName, array_keys($this->getParams())) || in_array($paramName, [get_called_class()::getPKName()])) {
                    $prepareParamsArray[$paramName] = $paramValue;
                    $signsArray[] = '?';
                }
            $newQueryPrepare .= implode(',', $prepareParamsArray);
            $newQueryPrepare .= ") VALUES (";
            $newQueryPrepare .= implode(',', $signsArray);
            $newQueryPrepare .= ")";
            $query = $db->prepare($newQueryPrepare);
            if (in_array(get_called_class()::getPKName(), array_keys($this->getParams()))) {
                $valuesArray = array_values($this->getParams());
            } else {
                $valuesArray = array_merge(["1"], array_values($this->getParams()));
            }
            $query->execute($valuesArray);
            if ($db->errorInfo()[2] !== null) {
                $returnArray['errorMessage'] = $db->errorInfo()[2];
            } else {
                $requestResult = $this->findAnyByParam($this->getParams());
                if ($requestResult['statusRequest']) {
                    if (count($requestResult['dataArray']) > 0) {
                        $returnArray['statusRequest'] = true;
                        $returnArray['addingModelPK'] = $requestResult['dataArray'][0][$this->getPKName()];
                    } else {
                        $returnArray['errorMessage'] = 'Созданнная запись не найдена.';
                    }
                } else {
                    $returnArray['errorMessage'] = $requestResult['errorMessage'];
                }
            }
        } else {
            $returnArray['errorMessage'] = " Отсутствует подключение к базе данных.";

        }
        return $returnArray;
    }
}