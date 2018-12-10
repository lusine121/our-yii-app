<?php
/**
 * Created by PhpStorm.
 * User: lusine
 * Date: 18.10.18
 * Time: 9:00
 */

namespace app\controllers;

use yii\web\Controller;


class AppController extends Controller
{
    public function debug($arr)
    {
        echo '<pre>' . print_r($arr, true) . '</pre>';
    }

}
