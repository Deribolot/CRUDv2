<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:28 AM
 */

namespace CRUD\Controllers;

/**
 * Class DepartmentController
 * @package CRUD\Controllers
 */
class DepartmentController extends LargeController
{
    /**
     *
     */
    protected function constuctTopButton()
    {
        $this->view['TopButtonsHTML']['ShowRowAddForm'] = ['name' => 'Добавить отдел', 'action' => 'ShowRowAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/'];
        $this->view['TopButtonsHTML']['ShowProgrammerUpdateForm'] = ['name' => 'Перевести программиста в отдел', 'action' => 'ShowProgrammerUpdateForm', 'href' => '/CRUDv2/' . $this->modelName . '/', 'color' => 'primary'];
    }

    /**
     * @param string $pk
     */
    public function actionShowProgrammerUpdateFormByPK(string $pk = "")
    {
        (new ProgrammerController())->actionShowProgrammerUpdateForm([$this->modelName => $pk], [$this->model::getPKName() => $pk]);
    }

    /**
     *
     */
    public function actionShowProgrammerUpdateForm()
    {
        (new ProgrammerController())->actionShowProgrammerUpdateForm([]);
    }

    /**
     *
     */
    public function actionUpdateProgrammer()
    {
        (new ProgrammerController())->actionUpdateProgrammer([], true);
    }

    /**
     *
     */
    public function actionUpdateCurrentProgrammer()
    {
        (new ProgrammerController())->actionUpdateProgrammer($this->constuctDerictoriesSelectedParams());
    }

    /**
     * @param string $pK
     */
    public function actionDeleteAccountingByPK(string $pK)
    {
        (new ProgrammerController())->actionDeleteByPK($pK, $this->modelName);
    }

}
