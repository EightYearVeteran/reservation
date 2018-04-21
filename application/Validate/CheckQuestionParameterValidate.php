<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.21
 * Time: 11:00
 */

namespace app\Validate;


use app\lib\Exception\WrongAnswerException;

class CheckQuestionParameterValidate extends BaseValidate
{
    protected $rule = [
        'answer' => 'require',
        'username' => 'require'
    ];

    protected $message = [
        'answer.require' => '答案为空, 请输入答案',
        'username.require' => '请输入学生号'
    ];

    public function checkUp()
    {
        $errormsg = $this->goCheck();

        if ($errormsg != null)
            throw new WrongAnswerException(['errorMessage' => $errormsg]);
    }
}