<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:47 AM
 */

namespace CRUD\Models;
use CRUD\Controllers\AccountingController;

/**
 * Class Programmer
 * @package CRUD\Models
 */
class Programmer extends Large
{
    /**
     * @var array
     */
    public const TABLE_PARAMS_DERICTORY = ['idProgrammer' => 'ID_PROGRAMMER', 'programmerToken' => 'PROGRAMMER_TOKEN', 'programmerSurname' => 'PROGRAMMER_SURNAME', 'programmerName' => 'PROGRAMMER_NAME', 'programmerPatronymic' => 'PROGRAMMER_PATRONYMIC', 'fIdDepartment' => 'FID_DEPARTMENT'];
    /**
     * @var array
     */
    public const FOREIGN_KEYS_TABLES_PARAMS_DERICTORIES = ['fIdDepartment' => ['entity' => 'Department', 'entityParam' => null]];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'PROGRAMMER';
    }

    /**
     * @return string
     */
    public static function getTablePKName(): string
    {
        return get_called_class()::TABLE_PARAMS_DERICTORY[get_called_class()::getPKName()];
    }

    /**
     * @return string
     */
    public static function getPKName(): string
    {
        return 'idProgrammer';
    }

    /**
     * @return string
     */
    public function getProgrammerToken(): string
    {
        return $this->getParamValue('programmerToken');
    }

    /**
     * @return string
     */
    public function getIdProgrammer(): string
    {
        return $this->getParamValue('idProgrammer');
    }

    /**
     * @return string
     */
    public function getProgrammerSurname(): string
    {

        return $this->getParamValue('programmerSurname');
    }

    /**
     * @return string
     */
    public function getProgrammerName(): string
    {
        return $this->getParamValue('programmerName');
    }

    /**
     * @return string
     */
    public function getProgrammerPatronymic(): string
    {
        return $this->getParamValue('programmerPatronymic');
    }

    /**
     * @return string
     */
    public function getFIdDepartment(): string
    {
        return $this->getParamValue('fIdDepartment');
    }

    /**
     * @param string $value
     */
    public function setIdProgrammer(string $value)
    {
        $valueIntegerString = get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
            $this->setParamValue('idProgrammer', $value);
    }

    /**
     * @param string $value
     */
    public function setProgrammerToken(string $value)
    {
        $valueIntegerString = get_called_class()::getStringIntegerNumber($value);
        if (!empty($valueIntegerString))
            $this->setParamValue('programmerToken', $value);
    }

    /**
     * @param string $value
     */
    public function setProgrammerSurname(string $value)
    {
        $this->setParamValue('programmerSurname', $value);
    }

    /**
     * @param string $value
     */
    public function setProgrammerName(string $value)
    {
        $this->setParamValue('programmerName', $value);
    }

    /**
     * @param string $value
     */
    public function setProgrammerPatronymic(string $value)
    {
        $this->setParamValue('programmerPatronymic', $value);
    }

    /**
     * @param string $value
     */
    public function setFIdDepartment(string $value)
    {
        $this->setParamValue('fIdDepartment', $value);
    }

    /**
     * @param string $pK
     * @return array
     */
    public static function findFullDataByPK(string $pK): array
    {
        $mainRequestResult = get_called_class()::findByPK($pK);
        $beautifulRequestResult = $mainRequestResult;
        if ($beautifulRequestResult['statusRequest']) {
            $beautifulRequestResult['dataArray'] = $mainRequestResult['dataArray'];
            $secondaryRrequestResult = Accounting::findAnyByParam([get_called_class()::getPKName() => $pK], ['QUALIFICATION' => ['qualificationName']], get_called_class()::getTableName());
            if (!$secondaryRrequestResult['statusRequest']) {
                $beautifulRequestResult = $secondaryRrequestResult;
            } else {
                $beautifulRequestResult['dataArray']['fDataArray']['qualifications'] = [];
                if (count($secondaryRrequestResult['dataArray']) > 0) {
                    $beautifulRequestResult['dataArray']['fDataArray']['qualifications']['dataArray'] = $secondaryRrequestResult['dataArray'];
                } else {
                    $beautifulRequestResult['errorMessage'] = 'Программист пока не владеет ни одним навыком.';
                }
            }
        }
        return $beautifulRequestResult;
    }

    /**
     * @return array
     */
    public static function findDirectories(array $params = []): array
    {
        $findDerictoriesAll = [];
        $findDerictoriesAll['departments'] = Department::findAll($params);
        if ($findDerictoriesAll['departments']['statusRequest']) {
            //если выполнен запрос
            return ['statusRequest' => true, 'dataArray' => ['departments' => $findDerictoriesAll['departments']['dataArray']], 'errorMessage' => ''];
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
     * @param array $post
     * @return array
     */
    public function fillAddData(array $post): array
    {
        $PKName = $this->getPKName();
        if (in_array($PKName, array_keys($post)) && !(empty($post[$PKName]))) {
            return ['isFull' => false, 'errorMessage' => ' При сохранении был передан id, которого не должно существовать у еще несохраненной записи. Этой записи в сохранении отказано. '];
        } else {
            $requiredParams = get_called_class()::TABLE_PARAMS_DERICTORY;
            unset($requiredParams[$PKName]);
            foreach ($requiredParams as $param => $value) {
                if (!in_array($param, array_keys($post)) || empty($post[$param])) {
                    if (strcmp('fIdDepartment', $param) !== 0) {
                        return ['isFull' => false, 'errorMessage' => ' Данные о некоторых обязательных для заполнения полях формы отсутствуют.'];
                    } else {
                        $this->$param = "";
                    }
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
    public function fillUpdateData(array $post): array
    {
        $requiredParams = get_called_class()::TABLE_PARAMS_DERICTORY;
        foreach ($requiredParams as $param => $value) {
            if (!in_array($param, array_keys($post))) {
                if (strcmp('fIdDepartment', $param) !== 0) {
                    return ['isFull' => false, 'errorMessage' => ' Данные о некоторых обязательных для заполнения полях формы отсутствуют.'];
                } else {
                    $this->$param = "";
                }
            } else {
                $this->$param = $post[$param];
            }
        }
        return ['isFull' => true, 'errorMessage' => ''];
    }

    /**
     * @param array $post
     * @return array
     */
    public function fillUpdateDepartmentData(array $post): array
    {
        if (!in_array('idProgrammer', array_keys($post)) || empty($post['idProgrammer'])) {
            return ['isFull' => false, 'errorMessage' => ' Данные о индивидуальном номере программиста отсутствуют.'];
        } else {
            $stringIntegerIdProgrammer = get_called_class()::getStringIntegerNumber($post['idProgrammer']);
            if (!empty($stringIntegerIdProgrammer)) {
                $this->idProgrammer = $stringIntegerIdProgrammer;
                if (!in_array('fIdDepartment', array_keys($post)) || empty(get_called_class()::getStringIntegerNumber($post['fIdDepartment']))) {
                    $this->fIdDepartment = "";
                } else {
                    $this->fIdDepartment = get_called_class()::getStringIntegerNumber($post['fIdDepartment']);
                }
                return ['isFull' => true, 'errorMessage' => ''];
            } else {
                return ['isFull' => false, 'errorMessage' => ' Данные о текущем индивидуальном номере программиста неверные.'];
            }
        }
    }

    /**
     * @return array
     */
    public
    function checkToAddRow(): array
    {
        $returnResult = $this->checkParam(['programmerToken']);
        if ($returnResult['statusFindSavingRowRequest'] && !$returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => true, 'errorMessage' => ''];
        } elseif ($returnResult['statusFindSavingRowRequest'] && $returnResult['findSavingRowRequestDataExist']) {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. Программист с таким индивидуальным номером уже существует. '];
        } else {
            return ['statusСheckSavingRowRequest' => false, 'errorMessage' => 'Произошла ошибка при проверке на уникальность записи. ' . $returnResult['errorMessage']];
        }
    }


    /**
     * @return array
     */
    public function checkToUpdateRow(): array
    {
        $getFindByPKRequest = get_called_class()::findByPK($this->pK);
        if ($getFindByPKRequest['statusRequest']) {
            $params = $this->getParams();
            $returnArray = ['statusСheckSavingRowRequest' => false, 'errorMessage' => ""];
            $requestResult = $this->findAnyByParam(['programmerToken' => $params['programmerToken']]);
            if ($requestResult['statusRequest']) {
                if (count($requestResult['dataArray']) > 0) {
                    foreach ($requestResult['dataArray'] as $accounting) {
                        if (strcmp($accounting[get_class()::getPKName()], get_class()::getPK()) !== 0) {
                            $returnArray['errorMessage'] = 'Произошла ошибка при проверке на уникальность записи. Программист с таким индивидуальным номером уже существует. ';
                            return $returnArray;
                        }
                    }
                    if (!in_array('fIdDepartment', array_keys($params)) || empty($params['fIdDepartment'])) {
                        $params['fIdDepartment'] = "";
                    }
                    $requestResult = $this->findAnyByParam($params);
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
    public function checkToUpdateDepartment(): array
    {
        $getFindByPKRequest = get_called_class()::findByPK($this->pK);
        if ($getFindByPKRequest['statusRequest']) {
            $params = $this->getParams();
            $returnArray = ['statusСheckSavingRowRequest' => false, 'errorMessage' => ""];
            if (!in_array('fIdDepartment', array_keys($params)) || empty($params['fIdDepartment'])) {
                $params['fIdDepartment'] = "";
            }
            $requestResult = $this->findAnyByParam($params);
            if ($requestResult['statusRequest']) {
                if (count($requestResult['dataArray']) > 0) {
                    $returnArray['errorMessage'] = 'Произошла ошибка при проверке на уникальность записи. Уровень навыка программиста не изменился, в сохранении отказано. ';
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
    public function addRow(): array
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
                $params = $this->getParams();
                if (!in_array('fIdDepartment', array_keys($params)) || empty($params['fIdDepartment'])) {
                    $params['fIdDepartment'] = "";
                }
                $requestResult = $this->findAnyByParam($params);
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


    /**
     * @return array
     */
    public
    static function findAll(array $params = []): array
    {
        return get_called_class()::findAnyByParam($params, [get_called_class()::getTableName() => ['programmerSurname', 'programmerName', 'programmerPatronymic', 'programmerToken']]);
    }

    /**
     * @return array
     */
    public
    static function findAbsolutelyAll(array $params = []): array
    {
        return Accounting::findAll($params);
    }


}