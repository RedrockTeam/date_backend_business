<?php
namespace Api\Controller;
use Think\Controller;
class PublicController extends Controller {
    //banner
    public function banner() {
        $data = M('banner')->where('status = 1')->field('banner')->select();
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //获取约会类型
    public function type()
    {
        $type_id = I('post.type_id');
        if ($type_id) {
            $data = M('date_type')->where(array('father_id' => $type_id))
                ->field('id as type_id, type as type_name')
                ->select();
        } else {
            $data = M('date_type')->where(array('father_id' => 0))
                ->field('id as type_id, type as type_name')
                ->select();
            foreach ($data as &$va) {
                $va['type_son'] = M('date_type')->where(array('father_id' => $va['type_id']))
                    ->field('id as type_id, type as type_name')
                    ->select();
            }
        }
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //获取学校
    public function school() {
        $data =  M('school')->field('id as school_id, schoolname as school_name')->select();
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

}
