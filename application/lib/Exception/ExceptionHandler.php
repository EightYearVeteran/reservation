<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 19:49
 */

namespace app\lib\Exception;

use Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $errorMessage;
    private $errorCode;


    /**
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     * 异常接管
     */
    public function render(Exception $e)
    {

        if ($e instanceof BaseException) { // 自定义已知的错误

            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
            $this->errorMessage = $e->errorMessage;

        } else {

            if (config('app_debug')) { // 调试阶段就用原生渲染界面

                return parent::render($e);

            } else { // 上线就返回json, 且是服务器内部未知错误, 已定义异常之外的异常

                $this->code = 500;
                $this->errorCode = 999;
                $this->message = "服务器内部错误><";

            }
        }

        $request = Request::instance();

        $result = [
            'message' => $this->errorMessage,
            'errorCode' => $this->errorCode,
            'url' => $request->url()
        ];

        return json($result, $this->code);
    }
}