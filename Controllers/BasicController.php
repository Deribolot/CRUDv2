<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:24 AM
 */

namespace CRUD\Controllers;

/**
 * Class BasicController
 * @package CRUD\Controllers
 */
abstract class BasicController
{
    /**
     * @var string
     */
    protected $template;
    /**
     * @var array
     */
    protected $view = [];

    /**
     *
     */
    abstract public function actionIndex();

    /**
     * BasicController constructor.
     * @param string $template
     */
    public function __construct(string $template = 'main.php')
    {
        $this->view['template'] = $template;
        $this->view['TopMenuHTML'] = [
            'accounting' => ['title' => 'Учет навыков программистов', 'href' => '/CRUDv2/accounting/', 'active' => false],
            'qualification' => ['title' => 'Навыки', 'href' => '/CRUDv2/qualification/', 'active' => false],
            'programmer' => ['title' => 'Программисты', 'href' => "/CRUDv2/programmer/", 'active' => false],
            'department' => ['title' => 'Отделы', 'href' => '/CRUDv2/department/', 'active' => false],
        ];
        $this->view['TopButtonsHTML'] = [];
        $this->view['MessageHTML'] = null;
    }

    /**
     * @param array $view
     */
    public static function constuctView(array $view)
    {
        foreach ($view as $key => $value) {
            if (strcmp($key, 'template') !== 0) {
                $$key = $value;
            }
        }
        if (in_array('template', array_keys($view))) {
            include(__DIR__ . '/../Views/' . $view['template']);
        }
    }

    /**
     * @return array
     */
    protected function constuctDerictoriesSelectedParams()
    {
        $params=[];
        $searchParam="f".ucfirst($this->model::getPKName());
        if (in_array($searchParam,array_keys($_POST))){
            $params[$this->model::getPKName()]=$_POST[$searchParam];
        }
        return $params;
    }

}