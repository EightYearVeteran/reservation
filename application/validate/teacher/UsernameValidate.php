<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 20:02
 */

namespace app\validate\teacher;


use app\validate\BaseValidate;

class UsernameValidate extends BaseValidate
{
    protected $rule = [
        'teacher_number' => 'require'
    ];

    protected $message = [
        'teacher_number.require' => '教工号为空'
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();
        if ($errormsg != null)
            throw new UsernameParameterException(['errorMessage' => $errormsg]);
    }
}