<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:48
 */

namespace app\lib\Exception\teacher;


use app\lib\Exception\BaseException;

class WrongPasswordException extends BaseException
{
    public $code = 200;
    public $errorCode = 3002;
    public $message = '密码错误, 请重试';
}