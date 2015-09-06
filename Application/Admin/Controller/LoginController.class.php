<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function index(){
        $this->display();
    }

    public function login() {
        $input = I('post.');
        $map = [
            'username' => $input['username'],
            'password' => md5(sha1($input['password']))
        ];
        $admin = M('admin');
        $data = $admin->where($map)->find();
        if(!$data) {
            $this->error('登录失败');
        }
        $update = [
            'lastip' => get_client_ip(),
            'time' => time()
        ];
        $admin->where(['id' => $data['id']])->save($update);
        session('admin_id', $data['id']);
        session('admin_name', $data['username']);
        $this->success('登录成功', U('Manage/index'));
    }

    public function logout() {
        session(null);
        return $this->redirect('Login/index');
    }
}