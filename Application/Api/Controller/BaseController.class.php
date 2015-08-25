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

    /**
     * @return bool
     * 用于返回当前时期是否是周末
     * 如果是，返回true，如果否，返回false
     */
    protected function weekendJudge () {
        $default = new \Org\Util\Date('2015-08-24');
        $nowTime = date('Y-m-d',time());
        $dayNum  = $default->dateDiff($nowTime);
        $dayNum  += 1 ;
        $week    = $dayNum / 7;
        $week    = floor($week);
        $week    = intval($week);
        $weekday = $dayNum % 7;
        $weekday = intval($weekday);
        if ($weekday != 0){
        } else {
            $weekday = 7 ;
        }
        return $weekday == 7 || $weekday == 6 ? true : false;
    }
}