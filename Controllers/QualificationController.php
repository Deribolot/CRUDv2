<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:28 AM
 */

namespace CRUD\Controllers;

/**
 * Class QualificationController
 * @package CRUD\Controllers
 */
class QualificationController extends LargeController
{
    /**
     *
     */
    protected function constuctTopButton()
    {
        $this->view['TopButtonsHTML']['ShowRowAddForm'] = ['name' => 'Добавить навык', 'action' => 'ShowRowAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/'];
        $this->view['TopButtonsHTML']['ShowAccountingAddForm'] = ['name' => 'Учесть навык программисту', 'action' => 'ShowAccountingAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/', 'color' => 'primary'];
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
        (new AccountingController())->actionDeleteByPK($pK,$this->modelName);
    }
}