<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:33
 */

namespace app\lib\Exception;


use think\Exception;

class BaseException extends Exception
{
    public $code = 500;
    public $message = 'unknown mistake';
    public $errorCode = 999;


    public function __construct($params = [])
    {
        if (!is_array($params)) return;

        if (array_key_exists('code', $params))
            $this->code = $params['code'];

        if (array_key_exists('errorCode', $params))
            $this->errorCode = $params['errorCode'];

        if (array_key_exists('message', $params))
            $this->message = $params['message'];
    }

}