<?php
namespace Api\Controller;
use Api\Model\CollectionModel;
use Api\Model\CommentModel;
use Api\Model\DateModel;
use Think\Controller;

class DateController extends BaseController {

    //获取约会类型
    public function type() {
        $type_id = I('post.type_id');
        if($type_id) {
           $data = M('date_type')->where(array('father_id' => $type_id))
                                ->field('id as type_id, type as type_name')
                                ->select();
        } else {
            $data = M('date_type')->where(array('father_id' => 0))
                                    ->field('id as type_id, type as type_name')
                                    ->select();
            foreach($data as &$va) {
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

    //获取约详情
    public function detailDate() {
        $date_id = I('post.date_id');
        $date = new DateModel();
        $data = $date->detaildate($date_id);
        $comment = new CommentModel();
        $data['date_comment'] = $comment->getComment(['date_id' => $date_id, $page = 1]);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //收藏约
    public function collectDate() {
        $input = I('post.');
        if(!is_numeric($input['uid']) || !is_numeric($input['date_id'])) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '非法数据'
            ]);
        }
        $collection = new CollectionModel();
        if($collection->addCollection($input)) {
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功'
            ]);
        } else {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你已经收藏过此约会'
            ]);
        }

    }
}