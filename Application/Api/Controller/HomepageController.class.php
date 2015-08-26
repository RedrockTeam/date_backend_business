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
        $page         = I('post.page');

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
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->count();
                $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->page("$page,10")->select();
            } else if ($timeLimit == 1) {
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->count();
                $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->page("$page,10")->select();
            } else {
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->count();
                $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->page("$page,10")->select();
            }
        } else {
            if ($timeLimit == 0) {
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->count();
                $res = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit'")->order("date_time")->page("$page,10")->select();
            } else if ($timeLimit == 1) {
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->count();
                $res = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND weekend = 1")->order("date_time")->page("$page,10")->select();
            } else {
                $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->count();
                $res = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("status = 2 AND gender_limit = '$genderLimit' AND cost_type = '$paymentLimit' AND date_time < '$timeLimit'")->order("date_time")->page("$page,10")->select();
            }
        }

        foreach ($res as $var) {
            $map ['user_id'] = $var ['uId'];
            $map ['date_id'] = $var ['id'];
            $check = M('date_praise')->where($map)->find();
            if ($check) {
                $var ['praise_status'] = 1;
            } else {
                $var ['praise_status'] = 0;
            }
        }


        $return = [
            'status' => '0',
            'info'   => 'success',
            'data'   => $res,
            'count'  => $count
        ];
        $this->ajaxReturn($return);
    }

    public function dateGroup () {
        $tel   = I('post.tel');
        $token = I('post.token');
        $type  = I('post.dateType');
        $page  = I('post.page');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }
        $db_date = M('date');

        $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("date_type.id = '$type'")->count();
        $res   = $db_date->field("date.id AS id,dausers.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("date_type.id = '$type'")->page("$page,10")->select();

        foreach ($res as $var) {
            $map ['user_id'] = $var ['uId'];
            $map ['date_id'] = $var ['id'];
            $check = M('date_praise')->where($map)->find();
            if ($check) {
                $var ['praise_status'] = 1;
            } else {
                $var ['praise_status'] = 0;
            }
        }

        $return = [
            'status' => '0',
            'info'   => 'success',
            'data'   => $res,
            'count'  => $count
        ];

        $this->ajaxReturn($return);
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
