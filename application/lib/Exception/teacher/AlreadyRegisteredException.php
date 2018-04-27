<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:22
 */

namespace app\lib\Exception\teacher;


use app\lib\Exception\BaseException;

class AlreadyRegisteredException extends BaseException
{
    public $code = 400;
    public $errorCode = 3004;
    public $errorMessage = '教工号已被注册';

}