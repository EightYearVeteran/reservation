<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:53
 */

namespace app\lib\Exception\Teacher;


use app\lib\Exception\BaseException;

class UnregisterException extends BaseException
{
    public $message = '用户名不存在';
    public $code = 200;
    public $errorCode = 3001;
}