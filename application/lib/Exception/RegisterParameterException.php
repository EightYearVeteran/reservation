<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.19
 * Time: 15:08
 */

namespace app\lib\Exception;


class RegisterParameterException extends BaseException
{
    public $code = 400; // 错误请求
    public $errorCode = 2003;
    public $errorMessage = '参数错误';
}