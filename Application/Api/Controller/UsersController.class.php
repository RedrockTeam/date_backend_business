<?php
namespace Api\Controller;
use Api\Model\ConcernModel;
use Api\Model\UsersModel;
use Think\Controller;
class UsersController extends BaseController {

    //获取个人信息
    public function info() {
        $input = I('post.', '');
        if($input['uid'] == $input['get_uid']) {
            $verify_self = true;
        } else {
            $verify_self = false;
            if($this->checkConcern($input)) {
                $verify_concern = true;
            } else {
                $verify_concern = false;
            }
        }
        $user = new UsersModel();
        $this->ajaxReturn($user->getInfo($input['uid'], $verify_self, $verify_concern));
    }

    private function checkConcern($input) {
        $concern = new ConcernModel();
        $map1 = [
            'from' => $input['uid'],
            'to' => $input['get_uid']
        ];
        $num1 = $concern->where($map1)->count();
        $map2 = [
            'from' => $input['get_uid'],
            'to' => $input['uid']
        ];
        $num2 = $concern->where($map2)->count();

        if($num1 == 1 && $num2 == 1) {
            return true;
        }

        return false;

    }
}