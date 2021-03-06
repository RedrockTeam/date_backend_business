<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/24
 * Time: 21:01
 */

namespace Api\Controller;
use Think\Controller;

class HomepageController extends Controller {

    public function filter () {
        $sortLimit    = I('post.sortLimit');
        $genderLimit  = I('post.genderLimit');
        $paymentLimit = I('post.paymentLimit');
        $timeLimit    = I('post.timeLimit');
        $page         = I('post.page');
        $type         = I('post.dateType');


        $db_date = M('date');

        $where = $this->whereCreate($type,$genderLimit,$paymentLimit,$timeLimit);

        if ($sortLimit == "1") {
            $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where($where)->count();
            $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where($where)->page("$page,20")->select();
        } else {
            $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where($where)->count();
            $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where($where)->page("$page,20")->order('date_time')->select();
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
        $type  = I('post.dateType');
        $page  = I('post.page');


        $db_date = M('date');

        $count = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("date_type.id = '$type'")->count();
        $res   = $db_date->field("date.id AS id,users.id AS uId,users.avatar AS avatar,users.nickname AS nickname,users.gender AS gender,users.role_id AS role,date.title AS title,date.content AS content,date_type.type AS type,date.comment_num AS comment,date.apply_num AS apply,date.praise AS praise,date.date_place AS place")->join("date_type ON date_type.id = date.date_type")->join("users ON users.id = date.user_id")->where("date_type.id = '$type'")->page("$page,20")->select();

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

    /**
     * @param $dateType
     * @param $gender
     * @param $payment
     * @param $time
     * @return array
     * 查询条件判定创建
     */
    private function whereCreate ($dateType,$gender,$payment,$time) {
        $timeLimit = $this->timeTrans($time);

        if ($timeLimit == 0) {
            if ($dateType == 0) {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2'
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender
                        ];
                    }
                }
            } else {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'date_type'   => $dateType
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'date_type'   => $dateType
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType
                        ];
                    }
                }
            }
        } else if ($timeLimit == 1) {
            if ($dateType == 0) {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'weekend'     => '1'
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'weekend'     => '1'
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender,
                            'weekend'     => '1'
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender,
                            'weekend'     => '1'
                        ];
                    }
                }
            } else {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'date_type'   => $dateType,
                            'weekend'     => '1'
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'date_type'   => $dateType,
                            'weekend'     => '1'
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType,
                            'weekend'     => '1'
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType,
                            'weekend'     => '1'
                        ];
                    }
                }
            }
        } else {
            if ($dateType == 0) {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    }
                }
            } else {
                if ($gender == 0) {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'date_type'   => $dateType,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'date_type'   => $dateType,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    }
                } else {
                    if ($payment == 0) {
                        $where = [
                            'date.status' => '2',
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    } else {
                        $where = [
                            'date.status' => '2',
                            'cost_type'   => $payment,
                            'gender_limit'=> $gender,
                            'date_type'   => $dateType,
                            'date_time'   => array('elt',$timeLimit)
                        ];
                    }
                }
            }
        }
        return $where;
    }
}
