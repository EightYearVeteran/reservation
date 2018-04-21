<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.21
 * Time: 10:21
 */

namespace app\lib\Exception;


class UsernameParameterException extends BaseException
{
    public $code = 401; // unauthorized
    public $errorCode = 2006;
    public $errorMessage;
}