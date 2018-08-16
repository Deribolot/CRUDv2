<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 9:08 AM
 */
ini_set('display_errors', 1);
require_once('vendor/autoload.php');
require_once('bd.php');

use CRUD\Controllers;
use CRUD\Others\Route;

echo Route::start();