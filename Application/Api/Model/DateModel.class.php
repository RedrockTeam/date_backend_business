<?php

namespace Api\Model;
use Think\Model;

class DateModel extends Model {

    protected $tableName  = 'date';

    public function getCreatedDate($input) {
        $map = [
            'date.user_id' => $input['uid']
        ];
        $limit = 10;
        $offset = ($input['page']-1)*$limit;
        return $this->where($map)
                    ->limit($offset, $limit)
                    ->join('join users on date.user_id = users.id')
                    ->field('users.id as uid, date.title, date.id as date_id, users.avatar, users.nickname, date.time, date.status as date_status')
                    ->select();
    }


}