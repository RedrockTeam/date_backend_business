<?php
namespace Admin\Controller;
use Think\Controller;
class ManageController extends BaseController {
    public function index() {
        $data = M('admin')->where(['admin.id' => session('admin_id')])->find();
        $this->assign('data', $data);
        $this->display();
    }

    public function judge() {
        $data = M('discover')->where(['discover.status' => 2])
                             ->join('JOIN users ON discover.user_id = users.id')
                             ->field(['discover.id as discover_id', 'users.id as uid', 'users.nickname', 'discover.title', 'discover.caption', 'discover.content', 'discover.picture'])
                             ->select();
        $this->assign('data', $data);
        $this->display();
    }
}