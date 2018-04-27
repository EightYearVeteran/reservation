<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:33
 */

namespace app\validate\teacher;


use app\lib\Exception\teacher\LoginParameterException;
use app\validate\BaseValidate;

class LoginValidate extends BaseValidate
{
    protected $rule = [
        'teacher_number' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'teacher_number.require' => '教工号为空',
        'password.require' => '密码为空',
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();
        if ($errormsg != null)
            throw new LoginParameterException(['errorMessage' => $errormsg]);
    }
}