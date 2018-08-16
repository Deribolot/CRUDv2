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
 * Class AccountingController
 * @package CRUD\Controllers
 */
class AccountingController extends SmallController
{
    /**
     *
     */
    protected function constuctTopButton()
    {
        $this->view['TopButtonsHTML']['ShowRowAddForm'] = ['name' => 'Учесть навык программиста', 'action' => 'ShowRowAddForm', 'href' => '/CRUDv2/' . $this->modelName . '/', 'color'=>'primary'];
    }

    /**
     *
     */
    public function actionUpdateQualificationRow()
    {
        $this->actionUpdateRow("qualification");
    }

    /**
     *
     */
    public function actionUpdateProgrammerRow()
    {
        $this->actionUpdateRow("programmer");
    }

    /**
     * @param string $pK
     */
    public function actionShowProgrammerRowUpdateFormByPK(string $pK)
    {
        $this->actionShowRowUpdateFormByPK($pK);
    }

    /**
     * @param string $pK
     */
    public function actionShowQualificationRowUpdateFormByPK(string $pK)
    {
        $this->actionShowRowUpdateFormByPK($pK);
    }

}