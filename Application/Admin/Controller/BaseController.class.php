<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
//        if(!session('admin_name') || !session('admin_id'))
//            return $this->redirect('Login/index');
    }

}