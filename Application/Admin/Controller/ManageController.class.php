<?php
namespace Admin\Controller;
use Think\Controller;
class ManageController extends BaseController {
    public function index() {
        $data = M('admin')->where(['id' => session('admin_id')])->find();
        $this->assign('data', $data);
        $this->display();
    }

    public function judge() {
        $data = M('discover')->where(['status' => 2])
                             ->join('JOIN users ON discover.user_id = users.id')
                             ->select();
        $this->assign('data', $data);
        $this->display();
    }
}