<?php
namespace Admin\Controller;
use Think\Controller;
class UserManageController extends BaseController {

    public function index() {
        $page = I('get.page');
        $data = M('users')->page($page, 30)
                          ->field('id as uid, nickname, gender, phone, status')
                          ->select();
        $this->assign('data', $data);
        $this->display();
    }
}