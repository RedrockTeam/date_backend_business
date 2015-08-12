<?php
namespace Api\Controller;
use Api\Model\ApplyModel;
use Api\Model\ConcernModel;
use Api\Model\DateModel;
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
        $data = $user->getInfo($input['uid'], $verify_self, $verify_concern);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //关注数
    public function careNum() {
        $input = I('post.');
        $concern = new ConcernModel();
        $data = [
            'care_num' => $concern->getCare($input),
            'cared_num' => $concern->getCared($input),
        ];
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }
    //获取发起的约会
    public function createdDate() {
        $input = I('post.');
        $date = new DateModel();
        $data = $date->getCreatedDate($input);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //获取参加的约会
    public function joinedDate() {
        $input = I('post.');
        $date = new ApplyModel();
        $data = $date->getJoinedDate($input);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //检查互相关注
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