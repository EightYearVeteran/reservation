<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 15:39
 */

namespace app\Validate;


use app\lib\Exception\BaseException;
use app\lib\Exception\NullNameException;
use app\lib\Exception\NullPasswordException;
use app\lib\Exception\NullUsernameException;
use app\lib\Exception\WrongPasswordException;
use app\lib\Exception\WrongUsernameException;

class RegisterValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require|length:9',
        'password' => 'require|min:6',
        'name' => 'require'
    ];

    protected $message = [
        'username.require' => '学号为空',
        'password.require' => '密码为空',
        'name.require' => '姓名为空',
        'username.length' => '学号不符合要求',
        'password.min' => '密码长度至少为6位'
    ];

    public function checkUp()
    {
        $result = $this->check($this->params());

        if (!$result) {
            $errormsg = $this->getError();
            if ($errormsg == '学号为空')
                throw new NullUsernameException();
            else if ($errormsg == '密码为空')
                throw new NullPasswordException();
            else if ($errormsg == '姓名为空')
                throw new NullNameException();
            else if ($errormsg == '学号不符合要求')
                throw new WrongUsernameException();
            else if ($errormsg == '密码长度至少为6位')
                throw new WrongPasswordException(['message' => $errormsg, 'errorCode' => 2007]);
            else
                throw new BaseException();
        }
    }
}