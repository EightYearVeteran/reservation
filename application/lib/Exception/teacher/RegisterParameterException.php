<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 18:52
 */

namespace app\lib\Exception\teacher;


use app\lib\Exception\BaseException;

class RegisterParameterException extends BaseException
{
    public $code = 400; // mistaken request
    public $errorCode = 3003;
    public $errorMessage = '参数错误';
}