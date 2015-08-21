<?php

namespace Api\Model;
use Think\Model;

class DiscoveryModel extends Model {

    protected $tableName  = 'discover';

    public function getDiscoverList($page) {
        $limit = 10;
        $page = $page > 0? $page : 1;
        $offset = ($page - 1) * $limit;
        $map = [
            'status' => 1
        ];
        $field = 'id as discover_id, title as discover_title, caption as discover_caption, picture as discover_picture, time as discover_time, praise as discover_praise, status as discover_status';
        return $this->where($map)
                    ->limit($offset, $limit)
                    ->field($field)
                    ->select();
    }

    public function getDiscover($discover_id) {
        $map = [
            'id' => $discover_id
        ];
        $field = 'id as discover_id, title as discover_title, caption as discover_caption, picture as discover_picture, time as discover_time, praise as discover_praise, status as discover_status';
        return $this->where($map)
                    ->field($field)
                    ->find();
    }
}