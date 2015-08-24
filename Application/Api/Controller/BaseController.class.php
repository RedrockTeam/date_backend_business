<?php
namespace Api\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function _initialize () {
        if (!$this->checkMethod()) {
            $data = [
                "status" => "-400",
                "info"   => "Please Use Post"
            ];
            $this->ajaxReturn($data);
        }
    }

    private function checkMethod () {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    public function _empty () {
        $data = [
            'status' => '-400',
            'info'   => 'Not Found'
        ];
        $this->ajaxReturn($data);
    }

    /**
     * @param $tel
     * @param $token
     * @return bool
     */
    protected function tokenCheck ($tel, $token) {
        $param = [
            'phone' => $tel,
            'token' => $token
        ];

        $res = M('users')->where($param)->find();

        return $res ? true : false;
    }

    /**
     * @param $tel
     * @return string
     * 用于token的创建
     */
    protected function tokenCreate ($tel) {
        $str    = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ!@#$%^&*()";
        $string = "";

        for ($i = 0;$i < 16;$i++) {
            $num     = mt_rand(0,71);
            $string .= $str [$num];
        }

        $token = md5(sha1($string));

        $update ['token'] = $token;
        M('users')->where("phone = '$tel'")->save($update);
        return $token;
    }
}