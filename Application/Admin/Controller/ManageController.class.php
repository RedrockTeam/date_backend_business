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

    //通过
    public function pass() {
        $id = I('post.id');
        M('discover')->where(['id' => $id])->save(['status' => 1]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }

    //不通过
    public function nopass() {
        $id = I('post.id');
        M('discover')->where(['id' => $id])->save(['status' => 3]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }
}