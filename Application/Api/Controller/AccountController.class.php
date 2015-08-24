<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/11
 * Time: 16:12
 */

namespace Api\Controller;
use Think\Controller;

class AccountController extends BaseController {
    private $appKey = "9bec9e05d8bc";
    private $smsCheckUrl = "https://api.sms.mob.com/sms/verify";

    private $avatar_arr = ['http://7xjdz6.com2.z0.glb.qiniucdn.com/S_B6d5d81a1e242154bc696eaf75327b0f1aed2d05b19230-j4kRdg_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_B7cb3fa8a1ac4e067a985d150aef56f5a4084b7a25e3cd-18xOsM_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_B72443a242121d3d5a3404307a5184a10d3f7a76a43207-RxUSzX_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Bc148b676fd10daa9a64b6e8999d83f9e4c5ecaac5dc54-7TRqdP_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Bc3ad72068bce22eb5be683225babfc54e9e458e217621-CsY8gD_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_F29210bdb497e132cc163ad10549de55445c6a8c121473-rHycIk_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_F09abe50a71e7dfc1ea1b176a8f827a75fe6df2aa8d00-IASSny_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Fde4f5566b00fdbbee9d12046cb6aa836b7d79f89d723-bB29dD_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Ff78d88972a74fbb5f34dd768c681e1573ff963e234f36-BKYuOG_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Fc6a74619b692f286dc9d2b0dc8636bbb6a2e45971026f-iTXY8S_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Ffc1f7e1c99a0c4bb556b26ad16c6f1bf7d5d969519000-WfoOak_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Faf401a198398e4c1021c463a029e179b9ff556882c50e-i7s57t_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_F6e6bc48e4f322b56d8edc3a5fd9db80fcd6e17129fe7-LHN0fm_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_F936e52cdda706160358cc0772431002b9cad882d82f3-Y3xOTe_fw658.jpg?imageView2/0/w/400','http://7xjdz6.com2.z0.glb.qiniucdn.com/S_Fd61f5ef17ebc553a1612ea4d940d76c3e7b34fd7121ee-ewJZ63_fw658.jpg?imageView2/0/w/400'];

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

        $telCheck = $this->telCheck($tel);
        if ($telCheck) {
            $return = [
                'status' => '-103',
                'info'   => 'Tel Already Exist'
            ];
            $this->ajaxReturn($return);
        }

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
            'signature'=> $signature
        ];

        if (!$avatar) {
            $num = mt_rand(0,14);
            $save ['avatar'] = $this->avatar_arr[$num];
        }
        M('users')->add($save);
        $res = M('users')->where($save)->find();
        $user_id = $res ['id'];

        if ($hobby) {
            $this->hobby($hobby,$user_id);
        }



        $this->ajaxReturn([
            'status' => '0',
            'info'   => '你好，你已经成功注册约炮平台！！！'
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
            'school_id'    => $school,
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

    /**
     * @param $tel
     * @return bool
     */
    private function telCheck ($tel) {
        $res = M('users')->where("phone = '$tel'")->find();
        return $res ? true : false;
    }

    public function telDelete () {
        $tel = I('post.tel');
        M('users')->where("phone = '$tel'")->delete();
        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }

    public function test () {
        $img = I('post.img');

        $avatar = $img;
        if (!$img) {
            $num = mt_rand(0,14);
            $avatar = $this->avatar[$num];
        }

        $return = [
            'avatar' => $avatar
        ];
        $this->ajaxReturn($return);
    }
}