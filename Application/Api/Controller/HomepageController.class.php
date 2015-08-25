<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/24
 * Time: 21:01
 */

namespace Api\Controller;
use Think\Controller;

class HomepageController extends BaseController {

    public function filter () {


    }

    public function dateGroup () {

    }

    public function dateInfo () {
        $tel   = I('post.tel');
        $token = I('post.token');
        $date  = I('post.dateId');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }


    }


}
