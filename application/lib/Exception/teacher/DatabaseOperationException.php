<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.5.4
 * Time: 16:48
 */

namespace app\lib\Exception\teacher;


use app\lib\Exception\BaseException;

class DatabaseOperationException extends BaseException
{
    public $code = 200;
    public $errorCode = 30000;
    public $errorMessage = "数据库操作异常";
}