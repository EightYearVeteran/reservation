<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 16:05
 */

namespace app\lib\Exception;

/**
 * 学号为空
 * Class NullUsernameException
 * @package app\lib\Exception
 */
class NullUsernameException extends BaseException
{
    public $code = 200;
    public $errorCode = 2003;
    public $message = '学号为空';
}