<?php
namespace Api\Controller;
use Think\Controller;
class LoginController extends Controller {
//登录
    public function login() {
        $input = I('post.');
        if(!isset($input['username']) || !isset($input['password']) || $input['username'] == null || $input['password'] == null){
            $data = [
                'status' => 403,
                'info' => '参数错误'
            ];
            $this->ajaxReturn($data);
        }
        if(!$this->verify($input['username'], $input['password'])) {
            $data = [
                'status' => 403,
                'info' => '身份认证错误还想约炮?!'
            ];
            $this->ajaxReturn($data);
        }
        else{
            $data = [
                'status' => 200,
                'info' => '登录成功, 可以开始约炮→_→',
                'nickname' => session('nickname'),
                'head' => session('head'),
                'token' => session('token'),
                'uid' => session('uid')
            ];
            $this->ajaxReturn($data);
        }
    }
    //验证
    private function verify($username, $password) {
        $data = [
            'user' => $username,
            'password' => $password
        ];
        //todo 从数据库验证
//        if($result->status == 200){
//            $user = new UsersModel();
//            $map = [
//                'stu_num' => $username
//            ];
//            $token = md5(time().$username);
//            session('token', $token);
//            if($user->where($map)->data(['updated_at' => time(),'token'=>$token])->save()) {
//                $info = $user->where($map)->find();
//                session('uid', $info['id']);
//                session('head', $info['head']);
//                session('nickname', $info['nickname']);
//                return true;
//            }
//            else {
//                $default_head = '';//todo 默认头像
//                $new = [
//                    'stu_num' => $username,
//                    'head' => $default_head,
//                    'signature' => '',
//                    'academy' => '',
//                    'qq' => '',
//                    'weixin' => '',
//                    'telephone' => '',
//                    'nickname' => $username,
//                    'created_at' => time(),
//                    'updated_at' => time(),
//                    'token' => $token
//                ];
//                $user->add($new);
//                $info = $user->where(['stu_num'=>$username])->find();
//                session('head', $default_head);
//                session('nickname', $info['nickname']);
//                session('uid', $info['id']);
//                return true;
//            }
//        }
//        else{
//            return false;
//        }
    }

}