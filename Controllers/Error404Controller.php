<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:24 AM
 */

namespace CRUD\Controllers;

/**
 * Class Error404Controller
 * @package CRUD\Controllers
 */
class Error404Controller extends BasicController
{
    /**
     *
     */
    public function actionIndex()
    {
        $this->view['ContentHTML'] = ['template' => 'error404.php'];
        $this->constuctView($this->view);
    }
}