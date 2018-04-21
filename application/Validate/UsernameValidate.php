<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.19
 * Time: 14:01
 */

namespace app\Validate;

use app\lib\Exception\UsernameParameterException;

class UsernameValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require'
    ];

    protected $message = [
        'username.require' => '学号为空'
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();
        if ($errormsg != null)
            throw new UsernameParameterException(['errorMessage' => $errormsg]);
    }
}