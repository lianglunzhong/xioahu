<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Answer;
use App\Model\Question;

class AnswerController extends Controller
{
    /** 新增回答 */
    public function add(Request $request) {
        $result = array();
        //判断用户是否登录
        $customer = $request->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        $question_id = $request->get('id');
        if(!$question_id) {
            $result['status'] = 0;
            $result['msg'] = 'question id is required!';
            return $result;
        }

        $question = Question::find($question_id);
        if(!$question) {
            $result['status'] = 0;
            $result['msg'] = 'question not exists!';
            return $result;
        }

        $content  = $request->get('content');
        if(!$content) {
            $result['status'] = 0;
            $result['msg'] = 'content is required!';
            return $result;
        }

        $answer = new Answer;
        $answer->customer_id = $customer->id;
        $answer->question_id = $question_id;
        $answer->content = $content;

        if($answer->save()) {
            $result['status'] = 1;
            $result['msg'] = 'add answer success!';
        } else {
            $result['status'] = 0;
            $result['msg'] = 'add answer false!';
        }

        return $result;
    }


    /** 更新回答 */
    public function change(Request $request) {
        $result = array();
        //判断用户是否登录
        $customer = $request->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        $id = $request->get('id');
        if(!$id) {
            $result['status'] = 0;
            $result['msg'] = 'question id is required!';
            return $result;
        }

        $answer = Answer::find($id);
        if(!$answer) {
            $result['status'] = 0;
            $result['msg'] = 'answer not exists!';
            return $result;
        }

        if($answer->customer_id != $customer->id) {
            $result['status'] = 0;
            $result['msg'] = 'permission  denied!';
            return $result;
        }

        $content = $request->get('content');
        $answer->content =$content;

        if($answer->save()) {
            $result['status'] = 1;
            $result['msg'] = 'success';
        } else {
            $result['status'] = 0;
            $result['msg'] = 'false';
        }

        return $result;
    }

    /** 查看回答 */
    public function read(Request $request) {
        $id = $request->get('id');
        $question_id = $request->get('question_id');

        $result = array();

        //查看单个问题
        if($id) {
            $answer = Answer::find($id);
            if($answer) {
                $result['status'] = 1;
                $result['data'] = $answer;
            } else {
                $result['status'] = 0;
                $result['msg'] = 'answer not found!';
            }
            
            return $result;
        }

        if(!$question_id) {
            $result['status'] = 0;
            $result['msg'] = 'question_id is required!';
        }

        $question = Question::find($question_id);
        if(!$question) {
            $result['status'] = 0;
            $result['msg'] = 'question not exists!';
        }

        $answers = Answer::where('question_id', $question_id)->get()->keyBy('id');

        $result['status'] = 1;
        $result['data'] = $answers;

        return $result;

    }

}
