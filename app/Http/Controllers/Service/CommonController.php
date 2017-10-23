<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Answer;
use App\Model\Customer;
use App\Model\Question;

class CommonController extends Controller
{
    /** 投票 */
    public function vote(Request $request) {
        $result = array();
        //判断用户是否登录
        $customer = $request->session()->get('customer');
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'login required!';
            return $result;
        }

        $answer_id = $request->get('id');
        $vote = $request->get('vote');
        if((!$answer_id) || (!$vote)) {
            $result['status'] = 0;
            $result['msg'] = 'answer_id and vote required!';
            return $result;
        }

        $answer = Answer::find($answer_id);
        if(!$answer) {
            $result['status'] = 0;
            $result['msg'] = 'answer not exists!';
            return $result;
        }

        //1:赞成 2：反对
        $vote = $vote <= 1 ?: 2;

        //清空该用户之前的投票
        $answer->customers()
                ->newPivotStatement() //查询关联表
                ->where('customer_id', $customer->id)
                ->where('answer_id', $answer_id)
                ->delete();

        $answer->customers()
                ->attach($customer->id, ['vote' => $vote]);

        $result['status'] = 1;

        return $result;
    }

    /** 时间线 */
    public function timeLine(Request $request) {

    	//分页限制
    	$limit = $request->get('limit', 5);
        $skip = ($request->get('page', 1) - 1) * $limit;

        $questions =  Question::orderBy('created_at', 'desc')
            ->with('customer')
            ->limit($limit)
            ->skip($skip)
            ->get();

        $answers =  Answer::orderBy('created_at', 'desc')
            ->with('customers')
            ->with('customer')
            ->limit($limit)
            ->skip($skip)
            ->get();

        //把两个结果集合并为一个数组
        $data = $questions->merge($answers);
        //按时间排序
        $data = $data->sortByDesc(function($item) {
        	return $item->created_at;
        });
        //去除默认给定的键
        $data = $data->values()->all();

        $result['status'] = 1;
        $result['data'] = $data;

        return response()->json($result);
    }
}
