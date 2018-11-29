<?php
namespace app\controllers;


use app\models\User;
use vendor\seek\Controller;

class Home extends Controller
{
    public function index()
    {
        $userModel = User::find()->where(['id' => 1])->select('id,name,description,age')->all();
        return [
            'users' => $userModel
        ];
    }
}