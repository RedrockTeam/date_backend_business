<?php
namespace Admin\Controller;
use Think\Controller;
class ManageController extends BaseController {
    public function index() {
        $data = M('admin')->where(['id' => session('admin_id')])->find();
        $this->assign('data', $data);
        $this->display();
    }


}