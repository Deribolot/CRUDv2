<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:29 AM
 */

namespace CRUD\Controllers;

/**
 * Class SmallController
 * @package CRUD\Controllers
 */
abstract class SmallController extends BasicController
{
    /**
     * @var object
     */
    protected $model;
    /**
     * @var string
     */
    protected $modelName;

    /**
     *
     */
    abstract protected function constuctTopButton();

    /**
     * SmallController constructor.
     * @param string $template
     */
    public function __construct(string $template = 'main.php')
    {
        parent::__construct($template);
        $this->constuctModel();
        $this->view['TopMenuHTML'] [strtolower($this->modelName)] ['active'] = true;
    }

    /**
     *
     */
    protected function constuctModel()
    {
        $namespaceArray = explode('\\', get_called_class());
        $this->modelName = str_replace('Controller', '', $namespaceArray[2]);
        $this->model = 'CRUD\Models\\' . $this->modelName;
    }

    /**
     *
     */
    public function actionIndex()
    {
        $this->actionFindAll();
    }

    /**
     *
     */
    public function actionFindAll()
    {
        $entity = $this->modelName . 's';
        $template = 'findAll' . $entity . '.php';
        if (method_exists($this->model,'findAbsolutelyAll')===true){
            $findAllResult = $this->model::findAbsolutelyAll();
        } else{
            $findAllResult = $this->model::findAll();
        }
        if ($findAllResult['statusRequest']) {
            if (count($findAllResult['dataArray']) > 0) {
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $findAllResult['dataArray'], 'message' => 'Найдено (' . count($findAllResult['dataArray']) . ') подходящих записей.'];
            } else {
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => [], 'message' => 'Подходящие записи отсутствуют.'];
            }
        } else {
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => [], 'message' => 'Произошла ошибка. ' . $findAllResult['errorMessage']];
        }
        $this->constuctTopButton();
        $this->constuctView($this->view);
    }

    /**
     * @param array $backArray
     * @param array $params
     */
    public function actionShowRowAddForm(array $backArray = [], array $params = [])
    {
        $entity = $this->modelName;
        $template = 'editing' . $entity . 'DataForm.php';
        $findDirectoriesResult = $this->model::findDirectories($params);
        if ($findDirectoriesResult['statusRequest']) {
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $this->model::parseFK($backArray), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => ''];
        } else {
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => [], strtolower($entity) . 'Derictories' => [], 'message' => 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage']];
        }
        $this->constuctView($this->view);
    }

    /**
     * @param string $pK
     */
    public function actionShowRowUpdateFormByPK(string $pK)
    {
        $entity = $this->modelName;
        $template = 'editing' . $entity . 'DataForm.php';
        $findByPKResult = $this->model::findByPK($pK);
        $findDirectoriesResult = $this->model::findDirectories();
        if ($findDirectoriesResult['statusRequest']) {
            if ($findByPKResult['statusRequest']) {
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $findByPKResult['dataArray'], strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => ''];
                $this->constuctView($this->view);
            } else {
                $this->view['MessageHTML'] = 'Произошла ошибка при получении определенной записи. ' . $findByPKResult['errorMessage'];
                $this->actionFindAll();
            }
        } else {
            $this->view['MessageHTML'] = 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage'];
            $this->actionFindAll();
        }
    }

    /**
     * @param string $nextEntity
     * @param array $params
     */
    public function actionAddRow(string $nextEntity = "", array $params = [])
    {
        $entity = $this->modelName;
        $template = 'editing' . $entity . 'DataForm.php';
        $modelObject = new $this->model();
        $fillModelObjectResult = $modelObject->fillAddData($_POST);
        $findDirectoriesResult = $this->model::findDirectories($params);
        if ($findDirectoriesResult['statusRequest']) {
            if ($fillModelObjectResult['isFull']) {
                $findAddingModelObjectResult = $modelObject->checkToAddRow();
                if ($findAddingModelObjectResult['statusСheckSavingRowRequest']) {
                    $addModelObjectResult = $modelObject->addRow();
                    if ($addModelObjectResult['statusRequest']) {
                        $this->view['MessageHTML'] = 'Запись успешно добавлена.';
                        $nextControllerName = "CRUD\Controllers\\" . ucfirst($nextEntity) . "Controller";
                        $findByPKResult = $this->model::findByPK($addModelObjectResult['addingModelPK']);
                        if (class_exists($nextControllerName)) {
                            $modelProperty = "fId" . ucfirst($nextEntity);
                            (new $nextControllerName())->actionFindByPK($findByPKResult['dataArray'][$modelProperty]);
                        } else {
                            if (method_exists($this, "actionFindByPK")) {
                                $this->actionFindByPK($addModelObjectResult['addingModelPK']);
                            } else {
                                (new ProgrammerController())->actionFindByPK($findByPKResult['dataArray']['fIdProgrammer']);
                            }
                        }
                    } else {
                        $message = 'Произошла ошибка при добавлении записи.' . $addModelObjectResult['errorMessage'];
                        $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                        $this->constuctView($this->view);
                    }
                } else {
                    $message = 'Произошла ошибка при попытке добавления записи в бд. ' . $findAddingModelObjectResult['errorMessage'];
                    $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                    $this->constuctView($this->view);
                }
            } else {
                $message = 'Не все поля корректно заполнены. ' . $fillModelObjectResult['errorMessage'];
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                $this->constuctView($this->view);
            }
        } else {
            $message = 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage'];
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => [], 'message' => $message];
            $this->constuctView($this->view);
        }

    }

    /**
     * @param string $nextEntity
     */
    public function actionUpdateRow(string $nextEntity = "")
    {
        $entity = $this->modelName;
        $template = 'editing' . $entity . 'DataForm.php';
        $modelObject = new $this->model();
        $findDirectoriesResult = $this->model::findDirectories();
        if ($findDirectoriesResult['statusRequest']) {
            $fillModelObjectResult = $modelObject->fillUpdateData($_POST);
            if ($fillModelObjectResult['isFull']) {
                $findUpdatedModelObjectResult = $modelObject->checkToUpdateRow();
                if ($findUpdatedModelObjectResult['statusСheckSavingRowRequest']) {
                    $addModelObjectResult = $modelObject->updateRow();
                    if ($addModelObjectResult['statusRequest']) {
                        $this->view['MessageHTML'] = 'Запись успешно обновлена.';
                        $nextControllerName = "CRUD\Controllers\\" . ucfirst($nextEntity) . "Controller";
                        if (class_exists($nextControllerName)) {
                            $modelProperty = "fId" . ucfirst($nextEntity);
                            (new $nextControllerName())->actionFindByPK($modelObject->$modelProperty);
                        } else {
                            $this->actionFindByPK($modelObject->pK);
                        }
                    } else {
                        $message = 'Произошла ошибка при обновлении записи.' . $addModelObjectResult['errorMessage'];
                        $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                        $this->constuctView($this->view);
                    }
                } else {
                    $message = 'Произошла ошибка при попытке обновления записи в бд. ' . $findUpdatedModelObjectResult['errorMessage'];
                    $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                    $this->constuctView($this->view);
                }
            } else {
                $message = 'Не все поля корректно заполнены. ' . $fillModelObjectResult['errorMessage'];
                $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => $findDirectoriesResult['dataArray'], 'message' => $message];
                $this->constuctView($this->view);
            }
        } else {
            $message = 'Произошла ошибка при получении справочных данных. ' . $findDirectoriesResult['errorMessage'];
            $this->view['ContentHTML'] = ['template' => $template, strtolower($entity) => $modelObject->getParams(), strtolower($entity) . 'Derictories' => [], 'message' => $message];
            $this->constuctView($this->view);
        }
    }

    /**
     * @param string $pK
     * @param string $nextEntity
     */
    public function actionDeleteByPK(string $pK, string $nextEntity = "")
    {
        $findByPKResult = $this->model::findByPK($pK);
        $deleteByPKResult = $this->model::deleteByPK($pK);
        $this->view['MessageHTML'] = $deleteByPKResult['errorMessage'];
        $nextControllerName = "CRUD\Controllers\\" . ucfirst($nextEntity) . "Controller";
        $modelProperty = "fId" . ucfirst($nextEntity);
        if (class_exists($nextControllerName) && array_key_exists($modelProperty, $findByPKResult['dataArray'])) {
            (new $nextControllerName())->actionFindByPK($findByPKResult['dataArray'][$modelProperty]);
        } else {
            $this->actionFindAll();
        }
    }
}