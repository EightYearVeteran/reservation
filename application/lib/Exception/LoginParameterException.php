<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:38
 */

namespace app\lib\Exception;


class LoginParameterException extends BaseException
{
    public $code = 400;
    public $errorMessage = "参数错误";
    public $errorCode = 2005;


}