<?php

/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 10:51 AM
 */


namespace CRUD\Others;

/**
 * Class Route
 */
class Route
{
    /**
     *
     */
    public static function start()
    {
        $answer = explode('?', $_SERVER['REQUEST_URI']);
        $routes = explode('/', $answer[0]);
        unset($routes[0]);
        unset($routes[1]);

        if (isset($routes[2]) && (strcmp($routes[2], '') != 0)) {
            $ControllerClass = ucfirst($routes[2]) . 'Controller';
        } else {
            $ControllerClass = 'ProgrammerController';
        }
        if (in_array($ControllerClass, ['BasicController', 'SmallController', 'LargeController'])) {
            $ControllerClass = 'Error404Controller';
        }
        $ControllerClass = "CRUD\Controllers\\" . $ControllerClass;
        if (!class_exists($ControllerClass)) {
            $ControllerClass = 'CRUD\Controllers\Error404Controller';
        }
        $actionMethod = 'action' . ucfirst(($_GET['action'] ?? 'Index'));
        if (!method_exists($ControllerClass, $actionMethod)) {
            $actionMethod = 'actionIndex';
        }

        $pK = null;
        if (strpos($actionMethod, 'ByPK') !== false) {
            $pKArray = [];
            if (isset($routes[3]) && preg_match("~\A\d+\Z~ui", $routes[3], $pKArray)) {
                $pK = $pKArray[0];
            } else {
                $ControllerClass = 'CRUD\Controllers\Error404Controller';
                $actionMethod = 'actionIndex';
            }
        }
        $Controller = new $ControllerClass();
        if (isset($pK)) {
            $Controller->$actionMethod($pK);
        } else {
            $Controller->$actionMethod();
        }
    }
}