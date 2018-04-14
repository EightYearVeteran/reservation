<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 18:44
 */

namespace app\Validate;


class loginValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require'
    ];
}