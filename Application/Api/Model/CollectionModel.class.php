<?php

namespace Api\Model;
use Think\Model;

class CollectionModel extends Model {

    protected $tableName  = 'collection';

    public function getCollection($input) {
        $map = [
            'collection.user_id' => $input['uid']
        ];
        $limit = 10;
        $offset = ($input['page']-1)*$limit;
        return $this->where($map)
                    ->limit($offset, $limit)
                    ->join('join date on collection.date_id = date.id')
                    ->join('join users on date.user_id = users.id')
                    ->field('users.id as uid, date.title, date.id as date_id, users.avatar, users.nickname, date.status as date_status')
                    ->select();
    }

}