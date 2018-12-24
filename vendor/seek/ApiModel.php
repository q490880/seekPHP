<?php
namespace vendor\seek;

use vendor\seek\tools\StringHelper;

abstract class ApiModel extends Model
{
    public $scenario;
    /*
     * 规则类型介绍
     * required     必填
     * integer      整形
     * string       字符串类型
     * min          字符串最短长度
     * max          字符串最大长度
     * email        邮箱类型
     * mobile       手机号码类型
     * url          URL类型
     * function     可以传入一个函数,参数是当前字段的值
     * result       返回值可以是字符串,也可以是一个数组,数组包括两个key,message(错误提示)和code(状态码)
     * html         HTML类型会转义JS代码
     * safe         SAFE类型不做任何验证
     *
     * 参考数据格式
      ===========================================================================
      'create' => [
          [['name', 'age'], 'required' ,'result' => '{filed} 不能为空'],
          [['sort', 'type'], 'integer'],
          [['name'], 'string', 'min' => 10, 'max' => 100],
          [['email'], 'email'],
          [['mobile'], 'mobile'],
          [['avatar'], 'url' ,'default' => 'http://i1.bjyltf.com/system/avatar.jpg'],
          [['shop_type'] ,
               'integer',
               'function' => function($shop_type){
                   return in_array($shop_type, [1, 2, 3]);
                },
               'result' => array(
                   'message' => '店铺类型错误', 'code' => 4000
               )
           ],
          [['price'], 'safe']
      ]
    ===============================================================================
     * */
    public function rules(){
        return [

        ];
    }


    public function attribute()
    {
        return [

        ];
    }

    public function scenario()
    {
        if(!$scenario = $this->scenario) {
            // 没有设置场景时,场景为当前方法的名称
            $scenario = App::getInstance()->action_id;
        }
        return $scenario;
    }

    public function load()
    {
        foreach ($_POST as $key => $value) {
            $this->attribute[$key] = $value;
        }
        if ($resultValidate = (new Validate($this))->validate()) {
            return $resultValidate;
        }
    }
}