<?php
namespace Api\Controller;
use Api\Model\UsersModel;
use Think\Controller;
class UsersController extends BaseController {

    //获取个人信息
    public function info() {
        $input = I('post.');
        $user = new UsersModel();
        $this->ajaxReturn($user->getInfo(1));
    }
}