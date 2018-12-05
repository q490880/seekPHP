<?php
namespace app\controllers;


use app\models\User;
use vendor\seek\Controller;


class Home extends Controller
{
    public function create()
    {
        $userModel = new User();
        $result = $userModel->load();
        return $result;
    }
}