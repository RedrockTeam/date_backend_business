<?php

namespace Api\Model;
use Think\Model;

class UserHobbyModel extends Model {

    protected $tableName  = 'user_hobby';

    //修改爱好
    public function editHobby($input) {
        $map = [
            'users.id' => $input['uid']
        ];
        $this->where($map)->delete();
        foreach($input['hobby'] as $hobby) {
            if(!$this->where($map)->add(['user_id' => $input['uid'], 'hobby_id' => $hobby])){
                return false;
            }
        }
        return true;
    }


}