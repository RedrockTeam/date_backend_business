<?php
namespace Admin\Controller;
use Think\Controller;
class BussinessController extends BaseController {
    public function index() {
        $page = I('get.page');
        $data = M('users')->page($page, 30)
                          ->where(['role_id' => 3])
                          ->field('id as uid, nickname, gender, phone, status')
                          ->select();
        $this->assign('data', $data);
        $this->display();
    }
}