<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Comment;
use App\Model\Question;
use App\Model\Answer;

class CommentController extends Controller
{
    /** 添加评论 */
    public function add(Request $request) {
        $result = array();
        //判断用户是否登录
        $customer = $request->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        //评论内容
        $content = $request->get('content');
        if(!$content) {
            $result['status'] = 0;
            $result['msg'] = 'content required!';
            return $result;
        }

        //问题id和回答id有且只能有一个
        $question_id = $request->get('question_id');
        $answer_id = $request->get('answer_id');
        if((!$question_id) && (!$answer_id)) {
            $result['status'] = 0;
            $result['msg'] = 'question_id or answer_id required!';
            return $result;
        }

        if($question_id && $answer_id) {
            $result['status'] = 0;
            $result['msg'] = 'question_id or answer_id required!';
            return $result;
        }

        $comment = new Comment;

        if($question_id) {
            //评论问题
            $question = Question::find($question_id);
            if(!$question) {
                $result['status'] = 0;
                $result['msg'] = 'question not exists！';
                return $result;
            }

            $comment->question_id = $question_id;
        } else {
            //评论回答
            $answer = Answer::find($answer_id);
            if(!$answer) {
                $result['status'] = 0;
                $result['msg'] = 'answer not exists！';
                return $result;
            }

            $comment->answer_id = $answer_id;
        }

        $reply_to = $request->get('reply_to');
        if($reply_to) {
            //评论评论
            $target = Comment::find($reply_to);
            if(!$target) {
                $result['status'] = 0;
                $result['msg'] = 'comment not exists！';
                return $result;
            }

            //不能评论自己的评论
            if($target->customer_id == $customer->id) {
                $result['status'] = 0;
                $result['msg'] = 'can not reply to yourself!';
                return $result;
            }

            $comment->reply_to = $reply_to;
        }

        $comment->content = $content;
        $comment->customer_id = $customer->id;

        if($comment->save()) {
            $result['status'] = 1;
            $result['id'] = $comment->id;
        } else {
            $result['status'] = 0;
            $result['msg'] = 'insert to db false！';
        }

        return $result;
    }

    /** 查看评论 */
    public function read(Request $request) {
        $result = array();
        $question_id = $request->get('question_id');
        $answer_id = $request->get('answer_id');
        if((!$question_id) && (!$answer_id)) {
            $result['status'] = 0;
            $result['msg'] = 'question_id or answer_id required!';
            return $result;
        }

        if($question_id) {
            $question = Question::find($question_id);
            if(!$question) {
                $result['status'] = 0;
                $result['msg'] = 'question not exists!';
                return $result;
            }

            $comments = Comment::where('question_id', $question_id)->get()->keyBy('id');
        }


        if($answer_id) {
            $answer = Answer::find($answer_id);
            if(!$answer) {
                $result['status'] = 0;
                $result['msg'] = 'answer not exists!';
                return $result;
            }

            $comments = Comment::where('answer_id', $answer_id)->get()->keyBy('id');
        }

        $result['status'] = 1;
        $result['data'] = $comments;

        return $result;
    }

    /** 删除评论 */
    public function remove(Request $request) {
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
            $result['msg'] = 'id is required!';
            return $result;
        }

        $comment = Comment::find($id);
        if(!$comment) {
            $result['status'] = 0;
            $result['msg'] = 'comment not exists!';
            return $result;
        }

        if($comment->customer_id != $customer->id) {
            $result['status'] = 0;
            $result['msg'] = 'permission denied!';
            return $result;
        }

        //先删除子评论i
        Comment::where('reply_to', $id)->delete();

        if($comment->delete()) {
            $result['status'] = 1;
            $result['msg'] = 'success!';
        } else {
            $result['status'] = 0;
            $result['msg'] = 'error';
        }

        return $result;
    }
}
