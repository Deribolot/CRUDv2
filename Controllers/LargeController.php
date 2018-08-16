<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:29 AM
 */

namespace CRUD\Controllers;

/**
 * Class LargeController
 * @package CRUD\Controllers
 */
abstract class LargeController extends SmallController
{
    /**
     * @param string $pK
     */
    public function actionFindByPK(string $pK)
    {
        $entity = $this->modelName;
        $template = 'findByPK' . $entity . '.php';
        $findByPKResult = $this->model::findFullDataByPK($pK);
        if ($findByPKResult['statusRequest']) {
            if (array_key_exists('fDataArray', $findByPKResult['dataArray'])) {
                foreach ($findByPKResult['dataArray']['fDataArray'] as $key => $value) {
                    $findByPKResult['dataArray']['fDataArray'][$key]['template'] = 'all' . ucfirst($key) . $entity . '.php';
                }
            }
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $findByPKResult['dataArray'], 'message' => $findByPKResult['errorMessage']];
            $this->constuctView($this->view);
        } else {
            $this->view['MessageHTML'] = 'Произошла ошибка. ' . $findByPKResult['errorMessage'];
            $this->actionFindAll();
        }
    }

    /**
     *
     */
    public function actionUpdateCurrentRow()
    {
        $this->actionUpdateRow();
    }

    /**
     * @param string $pK
     */
    public function actionShowCurrentRowUpdateFormByPK(string $pK)
    {
        $this->actionShowRowUpdateFormByPK($pK);
    }

}