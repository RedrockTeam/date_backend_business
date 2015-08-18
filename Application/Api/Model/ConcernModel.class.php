<?php

namespace Api\Model;
use Think\Model;

class ConcernModel extends Model {

    protected $tableName  = 'concern';

    //我的关注数
    public function getCare($input) {
        $map = [
            'from' => $input['uid']
        ];
        return $this->where($map)->count();
    }

    //我关注的人数量
    public function getCared($input) {
        $map = [
            'to' => $input['uid']
        ];
        return $this->where($map)->count();
    }

    //关注我的人列表
    public function careMe($input) {
        $page = $input['page'] > 0 ? $input['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $map = [
            'to' => $input['uid']
        ];
        return $this->where($map)
                    ->join('join users on concern.from = users.id')
                    ->limit($offset, $limit)
                    ->field('users.id as uid, users.nickname, users.avatar, users.signature users.charm, users.role_id')
                    ->select();
    }

    //我关注的人列表
    public function myCare($input) {
        $page = $input['page'] > 0 ? $input['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $map = [
            'from' => $input['uid']
        ];
        return $this->where($map)
                    ->join('join users on concern.from = users.id')
                    ->limit($offset, $limit)
                    ->field('users.id as uid, users.nickname, users.avatar, users.signature users.charm, users.role_id')
                    ->select();
    }
}