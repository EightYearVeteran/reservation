<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 18:53
 */

namespace app\validate\teacher;


use app\lib\Exception\teacher\RegisterParameterException;
use app\validate\BaseValidate;

class RegisterValidate extends BaseValidate
{
    protected $rule = [
        'teacher_number' => 'require',
        'password' => 'require',
        'name' => 'require',
    ];

    protected $message = [
        'teacher_number.require' => '教职工号为空',
        'password.require' => '密码为空',
        'password.length' => '密码长度至少为6',
        'name' => '真实姓名为空'
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();

        if ($errormsg != null)
            throw new RegisterParameterException(['errorMessage' => $errormsg]);
    }
}