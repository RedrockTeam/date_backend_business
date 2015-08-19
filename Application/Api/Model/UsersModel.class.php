<?php

namespace Api\Model;
use Think\Model;

class UsersModel extends Model {

    protected $tableName  = 'users';

    //获取用户信息
    public function getInfo($uid, $verify_self = false, $verify_concern = false){
        $map = [
            'users.id' => $uid
        ];
        $field = 'users.id as uid, nickname, avatar, signature, gender, charm, role_id';
        if($verify_self) {
            $field = $field.', realname, phone';
        }
        if($verify_concern) {
            $field = $field.', phone';
        }
        $data = $this->where($map)->field($field)->find();
        $data['hobby'] = $this->where($map)
                                ->join("join user_hobby on users.id = user_hobby.user_id")
                                ->join('join hobby on user_hobby.hobby_id = hobby.id')
                                ->select();
        return $data;
    }

    //修改签名
    public function editSignature($input) {
        $map = [
            'users.id' => $input['uid']
        ];
        if($this->where($map)->save(['signature' => $input['signature']])){
            return true;
        }
        return false;
    }

    //修改密码
    public function editPassword($input) {
        $map = [
            'id' => $input['uid']
        ];
        $account = $this->where($map)->getField('account');
        $oldPassword = $this->where($map)->getField('password');
        $head = substr($account,1,3);
        if($input['oldpassword'] != sha1(md5($oldPassword.$oldPassword).$head)) {
            return false;
        }
        $newPassword = sha1(md5($input['newpassword'].$input['newpassword']).$head);
        $this->where($map)->save(['password' => $newPassword]);
        return true;
    }

}