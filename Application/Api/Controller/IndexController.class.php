<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        $u = M('users')->select();
        print_r($u);
    }
}