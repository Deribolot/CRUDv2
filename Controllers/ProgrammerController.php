<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:28 AM
 */

namespace CRUD\Controllers;

use CRUD\Models\Accounting;
use CRUD\Models\Programmer;

/**
 * Class ProgrammerController
 * @package CRUD\Controllers
 */
class ProgrammerController extends LargeController
{
    /**
     *
     */
    protected function constuctTopButton()
    {
        $this->view['TopButtonsHTML']['ShowRowAddForm'] = ['name' => 'Добавить программиста', 'action' => 'ShowRowAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/'];
        $this->view['TopButtonsHTML']['ShowAccountingAddForm'] = ['name' => 'Учесть навык программиста', 'action' => 'ShowAccountingAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/', 'color' => 'primary'];
    }

    /**
     * @param array $backArray
     * @param array $params
     */
    public function actionShowProgrammerUpdateForm(array $backArray = [], array $params = [])
    {
        $entity = $this->modelName;
        $template = 'editingProgrammerDepartmentDataForm.php';
        $programmerModel = 'CRUD\Models\Programmer';
        $findDirectoriesResult = $programmerModel::findDirectories($params);
        $accountingModel = 'CRUD\Models\Accounting';
        $findAllProgrammers = $accountingModel::findDirectories();
        if ($findAllProgrammers['statusRequest']) {
            if ($findDirectoriesResult['statusRequest']) {
                if (empty($backArray)) {
                    $findDirectoriesResult['dataArray']['departments'][] = ['idDepartment' => '', 'departmentName' => 'отсутствует'];
                }
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $this->model::parseFK($backArray), 'programmerDerictories' => ['departments' => $findDirectoriesResult['dataArray']['departments'], 'programmers' => $findAllProgrammers['dataArray']['programmers']], 'message' => ''];
                $this->constuctView($this->view);
            } else {
                $this->view['MessageHTML'] = 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage'];
                if (!empty($backArray)) {
                    (new DepartmentController())->actionFindByPK($this->model::parseFK($backArray)['idDepartment']);
                } else {
                    (new DepartmentController())->actionFindAll();
                }
            }
        } else {
            $this->view['MessageHTML'] = 'Произошла ошибка при получении данных программистов. ' . $findAllProgrammers['errorMessage'];
            if (!empty($backArray)) {
                (new DepartmentController())->actionFindByPK($this->model::parseFK($backArray)['idDepartment']);
            } else {
                (new DepartmentController())->actionFindAll();
            }
        }
    }

    /**
     * @param array $params
     * @param bool $emptyValueAddingFlag
     */
    public function actionUpdateProgrammer(array $params = [], bool $emptyValueAddingFlag = false)
    {
        $entity = $this->modelName;
        $template = 'editingProgrammerDepartmentDataForm.php';
        $modelObject = new $this->model();
        $programmerModel = 'CRUD\Models\Programmer';
        $findDirectoriesResult = $programmerModel::findDirectories($params);
        $accountingModel = 'CRUD\Models\Accounting';
        $findAllProgrammers = $accountingModel::findDirectories();
        if ($findAllProgrammers['statusRequest']) {
            if ($findDirectoriesResult['statusRequest']) {
                if ($emptyValueAddingFlag) {
                    $findDirectoriesResult['dataArray']['departments'][] = ['idDepartment' => '', 'departmentName' => 'отсутствует'];
                }
                $fillModelObjectResult = $modelObject->fillUpdateDepartmentData($_POST);
                if ($fillModelObjectResult['isFull']) {
                    $findUpdatedModelObjectResult = $modelObject->checkToUpdateDepartment();
                    if ($findUpdatedModelObjectResult['statusСheckSavingRowRequest']) {
                        $addModelObjectResult = $modelObject->updateRow();
                        if ($addModelObjectResult['statusRequest']) {
                            $this->view['MessageHTML'] = 'Запись успешно обновлена.';
                            (new DepartmentController())->actionFindByPK($modelObject->getFIdDepartment());
                        } else {
                            $message = 'Произошла ошибка при обновлении записи.' . $addModelObjectResult['errorMessage'];
                            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), 'programmerDerictories' => ['departments' => $findDirectoriesResult['dataArray']['departments'], 'programmers' => $findAllProgrammers['dataArray']['programmers']], 'message' => $message];
                            $this->constuctView($this->view);
                        }
                    } else {
                        $message = 'Произошла ошибка при попытке обновления записи о программисте в бд. ' . $findUpdatedModelObjectResult['errorMessage'];
                        $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), 'programmerDerictories' => ['departments' => $findDirectoriesResult['dataArray']['departments'], 'programmers' => $findAllProgrammers['dataArray']['programmers']], 'message' => $message];
                        $this->constuctView($this->view);
                    }
                } else {
                    $message = 'Не все поля корректно заполнены. ' . $fillModelObjectResult['errorMessage'];
                    $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), 'programmerDerictories' => ['departments' => $findDirectoriesResult['dataArray']['departments'], 'programmers' => $findAllProgrammers['dataArray']['programmers']], 'message' => $message];
                    $this->constuctView($this->view);
                }

            } else {
                $this->view['MessageHTML'] = 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage'];
                (new DepartmentController())->actionFindAll();

            }
        } else {
            $this->view['MessageHTML'] = 'Произошла ошибка при получении данных программистов. ' . $findAllProgrammers['errorMessage'];
            (new DepartmentController())->actionFindAll();
        }
    }

    /**
     * @param string $pK
     */
    public function actionShowCurrentDepartmentRowUpdateFormByPK(string $pK)
    {
        $this->actionShowRowUpdateFormByPK($pK);
    }
    /**
     *
     */
    public function actionUpdateCurrentDepartmentRow()
    {
        $this->actionUpdateRow();
    }


    /**
     *
     */
    public function actionAddAccounting()
    {
        (new AccountingController())->actionAddRow($this->modelName);
    }

    /**
     *
     */
    public function actionAddCurrentAccounting()
    {
        (new AccountingController())->actionAddRow($this->modelName, $this->constuctDerictoriesSelectedParams());
    }

    /**
     * @param string $pk
     */
    public function actionShowAccountingAddFormByPK(string $pk = "")
    {
        (new AccountingController())->actionShowRowAddForm([$this->modelName => $pk], [$this->model::getPKName() => $pk]);
    }

    /**
     *
     */
    public function actionShowAccountingAddForm()
    {
        (new AccountingController())->actionShowRowAddForm([]);
    }

    /**
     * @param string $pK
     */
    public function actionDeleteAccountingByPK(string $pK)
    {
        (new AccountingController())->actionDeleteByPK($pK, $this->modelName);
    }
}