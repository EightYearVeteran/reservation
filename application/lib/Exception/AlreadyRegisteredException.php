<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.19
 * Time: 13:04
 */

namespace app\lib\Exception;


class AlreadyRegisteredException extends BaseException
{
    public $code = 400;
    public $errorCode = 2004;
    public $message = '学号已经被注册过';
}