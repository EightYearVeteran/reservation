<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:33
 */

namespace app\lib\Exception;


use think\Exception;

class BaseException extends Exception
{
    public $code;
    public $message;
    public $errorCode;
}