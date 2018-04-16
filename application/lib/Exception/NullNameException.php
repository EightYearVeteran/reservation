<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 17:25
 */

namespace app\lib\Exception;


class NullNameException extends BaseException
{
    public $code = 200;
    public $errorCode = 2005;
    public $message = '姓名为空';
}