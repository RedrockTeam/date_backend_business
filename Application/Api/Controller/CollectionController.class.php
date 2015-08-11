<?php
namespace Api\Controller;
use Think\Controller;

class CollectionController extends BaseController {

    public function collection() {
        $input = I('post.');
        $input['page'] = $input['page'] > 0 ? $input['page'] : 1;
        
    }
}