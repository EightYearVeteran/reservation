<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 16:22
 */

namespace app\lib\Exception;


class WrongUsernameException extends BaseException
{
    public $code = 200;
    public $errorCode = 2006;
    public $message = '学号不符合要求'; // 长度为9位数
}