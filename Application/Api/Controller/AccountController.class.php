<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/11
 * Time: 16:12
 */

namespace Api\Controller;
use Think\Controller;

class AccountController extends Controller {
    private $appKey = "9bec9e05d8bc";
    private $smsCheckUrl = "https://api.sms.mob.com/sms/verify";

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
     * 注册接口
     */
    public function register () {
        $pwd      = I('post.password');
        $code     = I('post.code');
        $tel      = I('post.tel');
        $gender   = I('post.gender');
        $nickname = I('post.nickname');
        $avatar   = I('post.avatar');
        $signature= I('post.signature');
        $hobby    = I('post.hobby');

        //验证密码是否符合规则
        $pwdCheck = $this->pwdCheck($pwd);
        $error = $pwdCheck ['error'];
        if (!$error) {
            $return = $pwdCheck ['data'];
            $this->ajaxReturn($return);
        }

        //验证码验证
        $codeCheck = $this->smsCheck($code,$tel);
        if (!$codeCheck) {
            $return = [
                'status' => '-108',
                'info'   => 'Code is Error'
            ];
            $this->ajaxReturn($return);
        }


        $save = [
            'password' => $this->password($pwd),
            'phone'    => $tel,
            'gender'   => $gender,
            'nickname' => $nickname,
            'avatar'   => $avatar,
            'signature'=> $signature
        ];
        M('users')->add($save);
        $res = M('users')->where($save)->find();
        $user_id = $res ['id'];

        if (!is_null($hobby)) {
            $this->hobby($hobby,$user_id);
        }

        $this->ajaxReturn([
            'status' => '0'
        ]);
    }

    /**
     * 登陆接口
     */
    public function login () {
        $user = I('post.loginUser');
        $pwd  = I('post.password');

        $db_user = M('users');


        $tel = $user;
        $res = $db_user->where("phone = '$tel'")->find();


        if (!$res) {
            $return = [
                'status' => '-105',
                'info'   => 'Account Not Found'
            ];
            $this->ajaxReturn ($return);
        }

        $pwdSec = $this->password($pwd);
        $pwdSec == $res ['password'] ? $diff = false : $diff = true;

        if ($diff) {
            $return = [
                'status' => '-106',
                'info'   => 'Password Is Wrong'
            ];
            $this->ajaxReturn($return);
        }

        $tel = $res ['phone'];
        $id  = $res ['id'];
        $data    = [
            'token'     => $this->tokenCreate($tel),
            'uid'       => $res ['id'],
            'nickname'  => $res ['nickname'],
            'avatar'    => $res ['avatar'],
            'fans'      => $res ['fans_count'],
            'role_id'   => $res ['role_id'],
            'gender'    => $res ['gender'],
            'signature' => $res ['signature'],
            'scan'      => $res ['scan_count'],
            'charm'     => $res ['charm']
        ];

        $res = M('verify')->where("user_id = '$id' AND status = 1")->find();

        if ($res) {
            $data ['realname'] = $res ['real_name'];
            $data ['school']   = $res ['school'];
        } else {
            $data ['realname'] = null;
            $data ['school']   = null;
        }

        $res = M('user_hobby')->where("user_id = '$id'")->join("hobby ON hobby.id = user_hobby.hobby_id")->select();

        $i = 0;
        $hobby = "";
        foreach ($res as $var) {
            $i > 0 ? ($hobby .= ";") : true;
            $hobby .= $var ['hobby'];
            $i++;
        }
        $data ['hobby'] = $hobby;

        $return = [
            'status' => '0',
            'info'   => 'Success',
            'data'   => $data
        ];
        $this->ajaxReturn($return);
    }

    /**
     * 实名认证接口
     */
    public function realNameVerify () {
        $id       = I('post.id');
        $token    = I('post.token');
        $realName = I('post.realName');
        $school   = I('post.school');
        $stuCard  = I('post.stuCard');

        $res = M('users')->where("id = '$id'")->find();
        $tel = $res ['phone'];

        $res = $this->tokenCheck($tel,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'user_id'   => $id,
            'real_name' => $realName,
            'school'    => $school,
            'stuPic'    => $stuCard,
            'status'    => '0'
        ];
        M('verify')->add($save);

        $return = [
            'status' => '0',
            'info'   => 'Success'
        ];

        $this->ajaxReturn($return);
    }

    /**
     * 密码找回接口
     */
    public function passwordFind () {
        $tel     = I('post.phone');
        $code    = I('post.code');
        $pwd     = I('post.password');

        $codeCheck = $this->smsCheck($code,$tel);
        if (!$codeCheck) {
            $return = [
                'status' => '-108',
                'info'   => 'Code Is Error'
            ];
            $this->ajaxReturn($return);
        }

        $update = [
            'password' => $this->password($pwd)
        ];

        M('users')->where("phone = '$tel'")->save($update);

        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }


     /**
     * @param $code
     * @param $tel
     * @return bool
     */
    private function smsCheck ( $code, $tel) {
        $request = [
            'appkey' => $this->appKey,
            'phone'  => $tel,
            'zone'   => '86',
            'code'   => $code
        ];
        $string   = http_build_query($request);
        $httpHead = [
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->smsCheckUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHead);

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);
        $status   = $response ['status'];
        if ($status == 200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $pwd
     * @return string
     */
    private function password ($pwd) {
        $head = substr($pwd,2,4);
        $string = sha1(md5($pwd.$pwd).$head);
        return $string;
    }

    /**
     * @param $pwd
     * @return array
     * 用于检测密码是否符合规范
     */
    private function pwdCheck ($pwd) {
        $length = strlen($pwd);

        if ($length < 6) {
            $return = [
                'error' => false,
                'data'  => [
                    'status' => '-104',
                    'info'   => 'Password Length Must in 6-20'
                ]
            ];
            return $return;
        } else if ($length > 20) {
            $return = [
                'error' => false,
                'data'  => [
                    'status' => '-104',
                    'info'   => 'Password Length Must in 6-20'
                ]
            ];
            return $return;
        }

        $return = [
            'error' => true
        ];

        return $return;
    }

    /**
     * @param $tel
     * @return string
     * 用于token的创建
     */
    private function tokenCreate ($tel) {
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
     * @param $tel
     * @param $token
     * @return bool
     */
    private function tokenCheck ($tel, $token) {
        $param = [
            'phone' => $tel,
            'token' => $token
        ];

        $res = M('users')->where($param)->find();

        return $res ? true : false;
    }

    /**
     * @param $hobby_str
     * @param $userId
     * @return bool
     * 用于爱好的添加
     */
    private function hobby ($hobby_str,$userId) {
        $hobby_arr = explode(";",$hobby_str);

        $db_hobby  = M('user_hobby');

        foreach ($hobby_arr as $var) {
            $save = [
                'hobby_id' => $var,
                'user_id'  => $userId
            ];
            $db_hobby->add($save);
        }
        return true;
    }
}