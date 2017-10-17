<?php
namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Customer;
use App\Model\Answer;
use App\Model\Question;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /** 用户注册 */
    public function register(Request $request){
        /** 获取数据 */
        $username = $request->get('username');
        $password = $request->get('password');

        $result = array();

        /** 验证 */
        if(!$username) {
            $result['status'] = 0;
            $result['msg'] = '用户名不能为空！';
            return $result;
        }

        if(!$password) {
            $result['status'] = 0;
            $result['msg'] = '密码不能为空！';
            return $result;
        }

        /** 判断用户名是否已存在 */
        $customer_exist = Customer::where('username', $username)->exists();
        // $customer = Customer::where('username', $username)->first();
        if($customer_exist) {
            $result['status'] = 0;
            $result['msg'] = '用户名已存在！';
            return $result;
        }

        /** 保存数据 */
        // $hash_password = Hash::make($password);
        $customer = new Customer;
        $customer->username = $username;
        $customer->password = bcrypt($password);
        if($customer->save()) {
            $result['status'] = 1;
            $result['customer_id'] = $customer->id;
        } else {
            $result['status'] = 0;
            $result['msg'] = '注册失败！';
        }

        return $result;
    }


    /** 用户登录 */
    public function login(Request $request){
        /** 获取数据 */
        $username = $request->get('username');
        $password = $request->get('password');

        $result = array();

        /** 数据不能为空 */
        if(!$request->get('username')) {
            $result['status'] = 0;
            $result['msg'] = '用户名不能为空！';
            return $result;
        }

        if(!$request->get('password')) {
            $result['status'] = 0;
            $result['msg'] = '密码不能为空！';
            return $result;
        }

        /** 检查用户名是否存在 */
        $customer = Customer::where('username', $username)->first();
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = '用户不存在！';
            return $result;
        }

        /** 检查密码是否正确 */
        $hash_password = $customer->password;
        if(!Hash::check($password, $hash_password)) {
            $result['status'] = 0;
            $result['msg'] = '密码不正确！';
            return $result;
        }

        /** 将用户信息写入sessoin */
        session()->put('customer', $customer);
        
        $result['status'] = 1;
        $result['msg'] = '登录成功！';

        return $result;
    }


    /** 退出登录 */
    public function logout(Request $request) {
        session()->forget('customer');
        
        $result['status'] = 1;
        $result['msg'] = '退出成功！';

        return $result;
    }


    /** 获取用户信息 */
    public function read(Request $request) {
        $result = array();
        $id = $request->get('id');
        if(!$id) {
            $result['status'] = 0;
            $result['msg'] = 'id is required!';
            return $result;
        }

        $get = ['username', 'intro'];

        $customer = Customer::find($id, $get);
        if(!$customer) {
            $result['status'] = 0;
            $result['msg'] = 'customer not exists!';
            return $result;
        }

        // $answer_count = $customer->answers()->count();
        // $question_count = $customer->questions()->count();
        $answer_count = Answer::where('customer_id', $customer->id)->count();
        $question_count = Question::where('customer_id', $customer->id)->count();

        $data = $customer->toArray();
        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;

        $result['status'] = 1;
        $result['data'] = $data;
        return $result;
    }

    /**
     * 检查用户名是否存在
     */
    public function exists(Request $request) {
        /** 获取数据 */
        $username = $request->get('username');

        $count = Customer::where('username', $username)->count();

        $result['status'] = 1;
        $result['count'] = $count;

        return $result;
    }

}

