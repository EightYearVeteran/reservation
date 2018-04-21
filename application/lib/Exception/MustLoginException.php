<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.19
 * Time: 14:46
 */

namespace app\lib\Exception;


class MustLoginException extends BaseException
{
    public $code = 200;
    public $errorCode = 2011;
    public $message = "请先登录, 再执行其他操作";
}