<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.21
 * Time: 13:53
 */

namespace app\lib\Exception;


class FailMessage extends BaseException
{
    public $code = 200;
    public $errorCode = 20000;
    public $errorMessage = '操作失败';
}