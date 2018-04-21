<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 17:29
 */

namespace app\validate;


use app\lib\Exception\LoginParameterException;

class LoginValidate extends BaseValidate
{
    protected $rule = [
        ['username', 'require'],
        ['password', 'require']
    ];

    protected $message = [
        'username.require' => '学号为空',
        'password.require' => '密码为空'
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();

        if ($errormsg != null) {
            throw new LoginParameterException(['errorMessage' => $errormsg]);
        } else {
            return true;
        }
    }
}