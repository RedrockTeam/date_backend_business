<?php

namespace Api\Model;
use Think\Model;

class DateModel extends Model {

    protected $tableName  = 'date';

    //获取自己发布的约
    public function getCreatedDate($input) {
        $map = [
            'date.user_id' => $input['uid']
        ];
        $limit = 10;
        $offset = ($input['page']-1)*$limit;
        return $this->where($map)
                    ->limit($offset, $limit)
                    ->join('join users on date.user_id = users.id')
                    ->join('join date_type on date.date_type = date_type.id')
                    ->field('users.id as uid, date.title, date.content, date_type.type as date_type, date.cost_type, date.date_place, date.praise, date.id as date_id, users.avatar, users.nickname, users.signature, users.gender, users.grade, users.role_id, date.date_time, date.create_time, date.status as date_status, date.apply_num, date.comment_num')
                    ->select();
    }

    //获取约详情
    public function detaildate($date_id, $uid) {
        $date = $this->where(['id' => $date_id])
                     ->field('id as date_id, title, date_type, content, date_time, date_place, cost_type, limit_num as people_limit, gender_limit, status as date_status, user_id as uid, apply_num, weekend')
                     ->find();
        $userinfo = M('users')->where(['id' => $date['uid']])->field('avatar, gender, role_id')->find();
        $data = array_merge($date, $userinfo);
        $data['school_limit'] = M('date_limit')->where(['date_id' => $date_id])
                                               ->join('JOIN school ON date_limit.school_id = school.id')
                                               ->field('school.id as school_id, schoolname as school_name')
                                               ->select();
        $user_date_status = M('apply')->where(['date_id' => $date_id, 'user_id' => $uid])->getField('status');
        $data['user_date_status'] = $user_date_status? $user_date_status:4;
        return $data;
    }

    //获取约限制
    public function dateLimit($date_id) {
        $data = $this->where(['id' => $date_id])
                     ->field('date_time, limit_num as people_limit, gender_limit, status as date_status, user_id as uid, promise_num')
                     ->find();
        $data['school_limit'] = M('date_limit')->where(['date_id' => $date_id])
                                                ->join('JOIN school ON date_limit.school_id = school.id')
                                                ->field('school_id')
                                                ->select();
        return $data;
    }

}