<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:37
 */

namespace app\lib\Exception\teacher;


use app\lib\Exception\BaseException;

class LoginParameterException extends BaseException
{
    public $code = 200;
    public $errorCode = 3005;
    public $errorMessage = '参数错误';
}