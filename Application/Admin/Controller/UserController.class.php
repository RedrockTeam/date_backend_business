<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController {
    public function index(){
        return $this->display();
    }

    //修改密码
    public function editPassword() {
        $password = I('post.password');
        if($password == null) {
            $this->ajaxReturn([
                'status' => 403,
                'info'   => '密码不能为空',
            ]);
        }
        if(strlen($password) < 6) {
            $this->ajaxReturn([
                'status' => 403,
                'info'   => '密码太短',
            ]);
        }
        $hash = md5(sha1($password));
        M('admin')->where(['id'=>session('admin_id')])->save(['password' => $hash]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功',
        ]);
    }
}