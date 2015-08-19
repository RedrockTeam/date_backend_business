<?php

namespace Api\Model;
use Think\Model;

class ApplyModel extends Model {

    protected $tableName  = 'apply';

    public function getJoinedDate($input) {
        $map = [
            'apply.user_id' => $input['uid']
        ];
        $limit = 10;
        $offset = ($input['page']-1)*$limit;
        return $this->where($map)
            ->limit($offset, $limit)
            ->join('join date on apply.date_id = date.id')
            ->join('join users on date.user_id = users.id')
            ->field('users.id as uid, date.title, date.id as date_id, users.avatar, users.nickname, date.date_time, date.create_time, date.status as date_status')
            ->select();
    }

}