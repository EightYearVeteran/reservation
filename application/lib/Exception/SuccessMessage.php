<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.16
 * Time: 16:53
 */

namespace app\lib\Exception;


class SuccessMessage extends BaseException
{
    public $code = 200;
    public $errorCode = 10000;
    public $message = '操作成功';
}