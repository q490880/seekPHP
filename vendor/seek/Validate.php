<?php
namespace vendor\seek;
use vendor\seek\tools\StringHelper;

class Validate
{
    public $validateData;
    public $attribute;
    public $rule = [];
    public function __construct(ApiModel $model)
    {
        $rules = $model->rules();
        $this->attribute = $model->attribute();
        $this->validateData = &$model->attribute;
        if ($rules && isset($rules[$model->scenario()])) {
            $this->rule = $rules[$model->scenario()];
        }
    }

    public function validate()
    {
        foreach ($this->rule as $ruleKey => $ruleData) {
            $filedList = $ruleData[0];      // 所有要验证的字段
            if (empty($filedList)) {
                return false;
            }
            $verifyModel = $ruleData[1];    // 验证类型
            foreach ($filedList as $filed) {
                $requestValue = isset($this->validateData[$filed]) ? trim($this->validateData[$filed]) : '';
                if (empty($requestValue) && $verifyModel != 'required') {
                    // 没有提交内容,但该字段又不要求必填时跳出验证
                    if (isset($ruleData['default'])) {
                        $this->validateData[$filed] = $ruleData['default'];
                    }
                    continue;
                }
                if ($resultVerify = $this->$verifyModel($filed,$requestValue,$ruleData)) {
                    return $resultVerify;
                }
            }
        }
    }

    public function required($key,$value,$rule)
    {
        if($value === '' || $value == null) {
            return $this->message($key,$value,'required',$rule);
        }
    }

    public function integer($key,$value,$rule)
    {
        $this->validateData[$key] = (int)$value;
    }

    public function string($key,$value,$rule)
    {
        $requestValue = htmlentities($value, ENT_QUOTES, 'UTF-8');
        if (isset($singleRuleValue['min'])) {
            if (StringHelper::abslength($value) < $rule['min']) {
                return $this->message($key,$value,'min');
            }
        }
        if (isset($singleRuleValue['max'])) {
            if (StringHelper::abslength($value) > $rule['max']) {
                return $this->message($key,$value,'max');
            }
        }
    }

    public function email($key,$value,$rule)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->message($key,$value,'email',$rule);
        }
    }

    public function mobile($key,$value,$rule)
    {
        if (!(strlen($value) != 11 || substr($value,0,1) != 1)) {
            return $this->message($key,$value,'mobile',$rule);
        }
    }

    public function url($key,$value,$rule)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return $this->message($key,$value,'url',$rule);
        }
    }


    public function message($key,$value,$validateModel,$rule = [])
    {
        $resultCode = 400;
        if (isset($rule['result'])) {
            if (is_array($rule['result'])) {
                $resultCode = $rule['result']['code'];
                $resultMessage = $rule['result']['message'];
            } else {
                $resultMessage = $rule['result'];
            }
        } else {
            $message = [
                'required' => '{field} 不能为空',
                'min' => '{field} 必须大于 {value}',
                'max' => '{field} 必须小于 {value}',
                'mobile' => '手机号格式不正确',
                'url' => 'url格式不正确'
            ];
            if (!isset($message[$validateModel])) {
                throw new \Exception("{$validateModel} validateModel error");
            }
            $resultMessage = $message[$validateModel];
        }
        if (isset($this->attribute[$key])) {
            $key = $this->attribute[$key];
        }
        $resultMessage = str_replace("{field}", $key, $resultMessage);
        $resultMessage = str_replace("{value}", $value, $resultMessage);
        return ['message' => $resultMessage, 'code' => $resultCode];
    }
}