<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.21
 * Time: 11:02
 */

namespace app\lib\Exception;


class WrongAnswerException extends BaseException
{
    public $code = 204;
    public $errorCode = 2013;
    public $errorMessage;
}