<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/24
 * Time: 21:36
 */

namespace Api\Controller;
use Think\Controller;

class MessageController extends BaseController {

    public function getToken () {
        $tel   = I('post.tel');
        $token = I('post.token');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }

        $info  = M('users')->where("phone = '$tel'")->find();
        $id    = $info ['id'];
        $param = [
            'userId' => $id,
            'name'   => $info ['nickname'],
            'portraitUri' => $info ['avatar']
        ];

        $res = M('chat')->where("user_id = '$id'")->find();
        if (!$res) {
            $res = $this->curl($param);
        }

        $code = $res ['code'];
        if ($code != 200) {
            $return = [
                'status' => '-202',
                'info'   => '未知错误'
            ];
        } else {
            $save = [
                'user_id' => $info ['id'],
                'token'   => $res ['token'],
            ];
            M('message')->add($save);
            $return = [
                'status' => '0',
                'info'   => '成功',
                'token'  => $res ['token']
            ];
        }
        $this->ajaxReturn($return);
    }

    public function messageGroup () {
        $tel   = I('post.tel');
        $token = I('post.token');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }
        $info  = M('users')->where("phone = '$tel'")->find();
        $id    = $info ['id'];

        $list = M('message')->where("to_user = '$id'")->select();

        $return = [
            'status' => '0',
            'info'   => '成功',
            'data'   => $list
        ];
        $this->ajaxReturn($return);
    }

    public function messageOne () {
        $tel   = I('post.tel');
        $token = I('post.token');
        $m_id  = I('post.messageId');

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }

        $res = M('message')->where("m_id = '$m_id'")->find();

        $return = [
            'status' => '200',
            'info'   => '成功',
            'data'   => $res
        ];
        $this->ajaxReturn($return);
    }

}