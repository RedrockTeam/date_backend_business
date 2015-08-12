<?php

namespace Api\Model;
use Think\Model;

class ConcernModel extends Model {

    protected $tableName  = 'concern';

    public function getCare($input) {
        $map = [
            'from' => $input['uid']
        ];
        return $this->where($map)->count();
    }

    public function getCared($input) {
        $map = [
            'to' => $input['uid']
        ];
        return $this->where($map)->count();
    }


}