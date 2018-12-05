<?php
namespace app\models;
use vendor\seek\ApiModel;

class User extends ApiModel
{
    public function tableName()
    {
        return 'users';
    }

    public function attribute()
    {
        return [
            'name' => '姓名'
        ];
    }

    public function rules(){
        return [
            'create' => [
                [['name', 'sex', 'age'], 'required'],
                [['description'], 'string', 'max' => 100],
                [['name'], 'string', 'min' => 2, 'max' => 10],
                [['sex', 'age'], 'integer']
            ]
        ];
    }

}