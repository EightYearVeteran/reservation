<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 16:20
 */

namespace app\lib\Exception;


class NullPasswordException extends BaseException
{
    public $code = 200;
    public $errorCode = 2004;
    public $message = '密码为空';
}