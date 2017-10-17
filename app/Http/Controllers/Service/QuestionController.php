<?php

namespace App\Http\Controllers\Service;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Question;
use App\Http\Controllers\Service\CustomerController;

class QuestionController extends Controller
{
    /** 新增问题 */
    public function add(Request $requset) {
        $result = array();
        //判断用户是否登录
        $customer = $requset->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        $validator = Validator::make($requset->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = 0;
            $result['msg'] = $validator->errors()->all();
            return $result;
        }

        //标题
        $title = $requset->get('title');
        //描述
        $description = $requset->get('description');
        
        $question = new Question;
        $question->title = $title;
        $question->description = $description;
        $question->customer_id = $customer->id;

        if($question->save()) {
            $result['status'] = 1;
            $result['id'] = $question->id;
        } else {
            $result['status'] = 0;
            $result['msg'] = '新增问题失败！';
        }

        return $result;
    }


    /** 更新问题 */
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

        $question = Question::find($id);
        if(!$question) {
            $result['status'] = 0;
            $result['msg'] = 'question not exists';
            return $result;
        }

        if($customer->id != $question->customer_id) {
            $result['status'] = 0;
            $result['msg'] = 'permission denied!';
            return $result;
        }

        $title = $request->get('title');
        $description = $request->get('description');

        if($title) {
            $question->title = $title;
        }
        if($description) {
            $question->description = $description;
        }

        if($question->save()) {
            $result['status'] = 1;
            $result['msg'] = 'success';
        } else {
            $result['status'] = 1;
            $result['msg'] = 'error';
        }

        return $result;
    }


    /** 查看问题 */
    public function read(Request $request) {
        $id = $request->get('id');

        $result = array();

        //查看单个问题
        if($id) {
            $question = Question::find($id);
            if($question) {
                $result['status'] = 1;
                $result['data'] = $question;
            } else {
                $result['status'] = 0;
                $result['msg'] = 'question not found!';
            }
            
            return $result;
        }

        //默认查看问题列表
        $limit = $request->get('limit', 5);
        $skip = ($request->get('page', 1) - 1) * $limit;

        $questions =  Question::orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get()
            ->keyBy('id');

        $result['status'] = 1;
        $result['data'] = $questions;

        return $result;

    }

    
    /** 删除问题 */
    public function remove(Request $request) {
        $result = array();
        //判断用户是否登录
        $customer = $requset->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        $id = $requset->get('id');
        if(!$id) {
            $result['status'] = 0;
            $result['msg'] = 'question id is required!';
            return $result;
        }

        $question = Question::find($id);
        if(!$question) {
            $result['status'] = 0;
            $result['msg'] = 'question not exists';
            return $result;
        }

        if($customer->id != $question->customer_id) {
            $result['status'] = 0;
            $result['msg'] = 'permission denied!';
            return $result;
        }

        $question->delete();

        $result['status'] = 1;
        $result['msg'] = 'delect success !';

        return $result;
    }
}
