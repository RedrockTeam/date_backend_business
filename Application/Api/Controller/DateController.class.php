<?php
namespace Api\Controller;
use Think\Controller;

class DateController extends BaseController {

    public function type() {
        $type_id = I('post.');
        if($type_id) {
           $data = M('date_type')->where(array('father_id' => $type_id))
                                ->field('id as type_id, name as type_name')
                                ->select();
        } else {
            $data = M('date_type')->where(array('father_id' => 0))
                                    ->field('id as type_id, name as type_name')
                                    ->select();
            foreach($data as &$va) {
                $va['type_son'] = M('date_type')->where(array('father_id' => $va['type_id']))
                    ->field('id as type_id, name as type_name')
                    ->select();
            }
        }
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }
}