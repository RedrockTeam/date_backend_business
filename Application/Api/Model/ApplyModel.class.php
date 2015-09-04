<?php

namespace Api\Model;
use Think\Model;

class ApplyModel extends Model {

    protected $tableName  = 'apply';

    public function getJoinedDate($input) {
        $map = [
            'apply.user_id' => $input['uid']
        ];
        $limit = 20;
        $offset = ($input['page']-1)*$limit;
        $data = $this->where($map)
            ->limit($offset, $limit)
            ->join('join date on apply.date_id = date.id')
            ->join('join users on date.user_id = users.id')
            ->join('join date_type on date.date_type = date_type.id')
            ->field('users.id as uid, date.title, date.content, date_type.type as date_type, date.cost_type, date.date_place, date.praise, date.id as date_id, users.avatar, users.nickname, users.signature, users.gender, users.grade, users.role_id, date.date_time, date.create_time, date.status as date_status, date.apply_num, date.comment_num')
            ->select();
        foreach($data as &$value) {
            if(M('date_praise')->where(['user_id' => $input['uid'], 'date_id' => $value['date_id']])->count()) {
                $value['praise_status'] = 1;
            } else {
                $value['praise_status'] = 0;
            }
        }
        return $data;
    }

}