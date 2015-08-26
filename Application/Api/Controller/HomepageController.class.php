<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/24
 * Time: 21:01
 */

namespace Api\Controller;
use Think\Controller;

class HomepageController extends BaseController {

    public function filter () {
        $sortLimit    = I('post.sortLimit');
        $genderLimit  = I('post.genderLimit');
        $paymentLimit = I('post.paymentLimit');
        $timeLimit    = I('post.timeLimit');
        $tel          = I('post.tel');
        $token        = I('post.token');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }

        $db_date = M('date');

        $timeLimit = $this->timeTrans($timeLimit);
        if ($sortLimit == "1") {
            if ($timeLimit == 0) {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->select();
            } else if ($timeLimit == 1) {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->select();
            } else {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->select();
            }
        } else {
            if ($timeLimit == 0) {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->order("date_time")->select();
            } else if ($timeLimit == 1) {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->order("date_time")->select();
            } else {
                $count = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->count();
                $res   = $db_date->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->order("date_time")->select();
            }
        }

        $return = [
            'status' => '0',
            'info'   => 'success',
            'data'   => $res
        ];
    }

    public function dateGroup () {

    }

    public function dateInfo () {
        $tel   = I('post.tel');
        $token = I('post.token');
        $date  = I('post.dateId');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }


    }

    /**
     * @param $id
     * @return int
     * 通过参数折算，返回具体选择具体查询函数
     */
    private function timeTrans ($time) {
        $nowTime = strtotime(date('Y-m-d'));

        switch ($time) {
            case "1" :
                $returnTime = $nowTime + 86400;
                break;
            case "2" :
                $returnTime = $nowTime + 172800;
                break;
            case "3" :
                $returnTime = $nowTime + 604800;
                break;
            case "4" :
                $returnTime = 1;
                break;
            default:
                $returnTime = 0;
                break;
        }

        return $returnTime;
    }
}
