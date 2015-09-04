<?php

namespace Api\Model;
use Think\Model;

class DiscoveryModel extends Model {

    protected $tableName  = 'discover';

    public function getDiscoverList($page, $uid) {
        $limit = 20;
        $page = $page > 0? $page : 1;
        $offset = ($page - 1) * $limit;
        $map = [
            'status' => 1
        ];
        $field = 'id as discover_id, title as discover_title, caption as discover_caption, picture as discover_picture, time as discover_time, praise as discover_praise, status as discover_status, apply_num, comment_num, cost_type';
        $data = $this->where($map)
                    ->limit($offset, $limit)
                    ->field($field)
                    ->select();
        foreach($data as &$value) {
            if(M('discover_praise')->where(['user_id' => $uid, 'discover_id' => $value['discover_id']])->count()) {
                $value['praise_status'] = 1;
            } else {
                $value['praise_status'] = 0;
            }
        }
        return $data;
    }

    public function getDiscover($discover_id) {
        $map = [
            'id' => $discover_id
        ];
        $field = 'id as discover_id, title as discover_title, caption as discover_caption, picture as discover_picture, time as discover_time, praise as discover_praise, status as discover_status, content as discover_content, cost_type';
        return $this->where($map)
                    ->field($field)
                    ->find();
    }

    public function joinDiscover($input) {
        $map = [
            'id' => $input['discover_id']
        ];
        if($this->where($map)->getField('status') != 1) {
            return false;
        }
        $data = [
            'user_id' => $input['user_id'],
            'discover_id' => $input['discover_id'],
            'time' => time(),
            'status' => 1
        ];
        $this->add($data);
        $this->where($map)->setInc('apply_num');
        return true;
    }

}
