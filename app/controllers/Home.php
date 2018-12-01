<?php
namespace app\controllers;


use app\models\User;
use vendor\seek\Controller;

class Home extends Controller
{
    public function index()
    {
        $userModel = User::find()->select('id,name,description,age')->all();
        return [
            'users' => $userModel
        ];
    }
}