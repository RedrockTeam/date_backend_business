<?php
namespace Api\Controller;
use Api\Model\CollectionModel;
use Think\Controller;

class CollectionController extends BaseController {

    public function collection() {
        $input = I('post.');
        $input['page'] = $input['page'] > 0 ? $input['page'] : 1;
        $collection = new CollectionModel();
        $data = $collection->getCollection($input);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }
}