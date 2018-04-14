<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 19:49
 */

namespace app\lib\Exception;

use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $message;
    private $errorCode;


    public function render(\Exception $e) // \Exception是原生Exception
    {

        if ($e instanceof BaseException) { // 自定义已知的错误
            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
            $this->message = $e->message;

        } else {
            if (config('app_debug')) { // 调试阶段就用原生

                return parent::render($e);

            } else { // 上线就返回json, 且是服务器内部未知错误
                $this->code = 500;
                $this->errorCode = 999;
                $this->message = "服务器内部错误><";

            }
        }

        $request = Request::instance();

        $result = [
            'message' => $this->message,
            'errorCode' => $this->errorCode,
            'url' => $request->url()
        ];

        return json($result, $this->code);
    }
}