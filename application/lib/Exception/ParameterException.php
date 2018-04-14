<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:38
 */

namespace app\lib\Exception;



class ParameterException extends BaseException
{
    public $code = 400;
    public $message = "参数错误";
    public $errorCode = 10000;
}