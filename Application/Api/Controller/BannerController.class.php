<?php
namespace Api\Controller;
use Think\Controller;
class BannerController extends Controller {
    public function index() {
        $data = M('banner')->where('status = 1')->field('banner')->select();
        $this->ajaxReturn([
                    'status'=> 0,
                    'info' => '成功',
                    'data' => $data
        ]);
    }
}