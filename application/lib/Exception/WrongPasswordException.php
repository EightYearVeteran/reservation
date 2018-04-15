<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 20:34
 */

namespace app\lib\Exception;


class WrongPasswordException extends BaseException
{
    public $code = 200;
    public $errorCode = 2002;
    public $message = '密码错误, 请重试';
}