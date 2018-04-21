<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 15:39
 */

namespace app\validate;

use app\lib\Exception\RegisterParameterException;

class RegisterValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require|length:9',
        'password' => 'require|min:6',
        'name' => 'require',
        'college' => 'require',
        'specialty' => 'require',
        'email' => 'email',
        'phone' => 'length:11',
        'question' => 'require',
        'answer' => 'require'
    ];

    protected $message = [
        'username.require' => '学号为空',
        'username.length' => '学号不符合要求',
        'password.require' => '密码为空',
        'password.min' => '密码长度至少为6位',
        'name.require' => '姓名为空',
        'college.require' => '院系选择为空',
        'specialty.require' => '所在专业选择为空',
        'email.email' => '邮箱格式不正确',
        'phone.length' => '手机号码不正确',
        'question.require' => '请输入重置密码时的问题',
        'answer.require' => '请输入重置密码时问题的答案'
    ];


    public function checkUp()
    {
        $errormsg = $this->goCheck();

        if ($errormsg != null)
            throw new RegisterParameterException(['errorMessage' => $errormsg]);

    }

}