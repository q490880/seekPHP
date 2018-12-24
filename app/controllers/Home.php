<?php
namespace app\controllers;


use app\models\User;
use vendor\seek\Controller;


class Home extends Controller
{
    public function index()
    {
        $userModel = new User();
        $user = $userModel->where(['id' => 1])->asArray()->all();
    }
}