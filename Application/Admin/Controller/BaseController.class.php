<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
        if(!session('admin_name') || !session('admin_id'))
            return $this->error('请先登录', U('Login/index'));
    }

}