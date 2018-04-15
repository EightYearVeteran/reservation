<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 20:03
 */

namespace app\lib\Exception;


class UnregisterException extends BaseException
{
    public $message = '用户名不存在';
    public $code = 200;
    public $errorCode = 2001;
}