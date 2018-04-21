<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.21
 * Time: 11:52
 */

namespace app\lib\Exception;


class NotFoundException extends BaseException
{
    public $code = 404;
    public $errorCode = 2014;
    public $errorMessage = '未找到学生相关信息';
}