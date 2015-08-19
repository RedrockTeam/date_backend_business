<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/8/11
 * Time: 16:12
 */

namespace Home\Controller;
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
        $account  = I('post.account');
        $pwd      = I('post.password');
        $code     = I('post.code');
        $tel      = I('post.tel');
        $gender   = I('post.gender');
        $nickname = I('post.nickname');
        $avatar   = I('post.avatar');
        $signature= I('post.signature');
        $hobby    = I('post.hobby');

        //验证账户是否符合规则，判定账户是否已经存在
        $accountCheck = $this->accountCheck($account);
        if ($accountCheck ['error'] == "1") {
            $return = $accountCheck ['data'];
            $this->ajaxReturn($return);
        }

        //验证密码是否符合规则
        $pwdCheck = $this->pwdCheck($pwd);
        if ($pwdCheck ['error'] == '1') {
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
            'account'  => $account,
            'password' => $this->password($pwd),
            'phone'    => $tel,
            'gender'   => $gender,
            'nickname' => $nickname,
            'avatar'   => $this->imgTrans($avatar,2),
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

        if ($this->userJudge($user)) {
            $account = $user;
            $res     = $db_user->where("account = '$account'")->find();
        } else {
            $tel = $user;
            $res = $db_user->where("phone = '$tel'")->find();
        }

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

        $account = $res ['account'];
        $data    = [
            'token' => $this->tokenCreate($account)
        ];

        $return = [
            'status' => '0',
            'data'   => $data
        ];
        $this->ajaxReturn($return);
    }

    public function realNameVerify () {
        $account  = I('post.account');
        $token    = I('post.token');
        $realName = I('post.realName');
        $school   = I('post.school');
        $stuCard  = I('post.stuCard');

        $res = M('users')->where("account = '$account'")->find();
        $user_id = $res ['id'];

        $res = $this->tokenCheck($account,$token);
        if (!$res) {
            $return = [
                'status' => '-109',
                'info'   => 'Token Error'
            ];
            $this->ajaxReturn($return);
        }

        $save = [
            'user_id'   => $user_id,
            'real_name' => $realName,
            'school'    => $school,
            'stuPic'    => $this->imgTrans($stuCard,1),
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
        $account = I('post.account');
        $tel     = I('post.phone');
        $code    = I('post.code');
        $pwd     = I('post.password');

        $phoneCheck = $this->phoneCheck($account,$tel);
        if (!$phoneCheck) {
            $return = [
                'status' => '-107',
                'info'   => 'Account And Phone Don\'t Match'
            ];
            $this->ajaxReturn($return);
        }

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

        M('users')->where("account = '$account'")->save($update);

        $return = [
            'status' => '0'
        ];
        $this->ajaxReturn($return);
    }

    /**
     * @param $account
     * @param $tel
     * @return bool
     */
    private function phoneCheck ($account, $tel) {
        $param   = [
            'account' => $account,
            'phone'   => $tel
        ];

        $res = M('users')->where($param)->find();

        return $res ? true : false;
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
        $response = json_decode($response);
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
     * @param $account
     * @return array
     * 检验注册的账户是否符合相应的规范
     */
    private function accountCheck ($account) {
        $head = substr($account, 0, 1);

        if (!preg_match("/^[a-zA-Z/",$head)) {
            $return = [
                'error' => '1',
                'data'  => [
                    'status' => '-101',
                    'info'   => 'Account First Must be a Letter'
                ]
            ];
            return $return;
        }

        $length = strlen($account);

        if ($length < 6) {
            $return = [
                'error' => '1',
                'data'  => [
                    'status' => '-102',
                    'info'   => 'Account Length Must in 6-16'
                ]
            ];
            return $return;
        } else if ($length > 16) {
            $return = [
                'error' => '1',
                'data'  => [
                    'status' => '-102',
                    'info'   => 'Account Length Must in 6-16'
                ]
            ];
            return $return;
        }

        $users = M('users');

        $res = $users->where("account = '$account'")->find();

        if ($res) {
            $return = [
                'error' => '1',
                'data'  => [
                    'status' => '-103',
                    'info'   => 'Account Already Exist'
                ]
            ];
        } else {
            $return = [
                'error' => '0'
            ];
        }

        return $return;
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
                'error' => '1',
                'data'  => [
                    'status' => '-104',
                    'info'   => 'Password Length Must in 6-20'
                ]
            ];
            return $return;
        } else if ($length > 20) {
            $return = [
                'error' => '1',
                'data'  => [
                    'status' => '-104',
                    'info'   => 'Password Length Must in 6-20'
                ]
            ];
            return $return;
        }

        $return = [
            'error' => '0'
        ];

        return $return;
    }

    /**
     * @param $account
     * @return string
     * 用于token的创建
     */
    private function tokenCreate ($account) {
        $str    = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ!@#$%^&*()";
        $string = "";

        for ($i = 0;$i < 16;$i++) {
            $num     = mt_rand(0,71);
            $string .= $str [$num];
        }

        $token = md5(sha1($string));

        $update ['token'] = $token;
        M('users')->where("account = '$account'")->save($update);
        return $token;
    }

    /**
     * @param $user
     * @return bool
     * 用于检测Account还是Tel
     */
    private function userJudge ($user) {
        $head = substr($user,0,1);

        $res  = preg_match("/^[a-zA-Z]/",$head);
        return $res ? true : false ;
    }

    /**
     * @param $account
     * @param $token
     * @return bool
     */
    private function tokenCheck ($account, $token) {
        $param = [
            'account' => $account,
            'token'   => $token
        ];

        $res = M('users')->where($param)->find();

        return $res ? true : false;
    }

    /**
     * @param $img_base
     * @return string
     *
     */
    private function imgTrans ($img_base,$type) {
        if ($type == 1) {
            $filePath = "Public/stuCard/";
        } else if ($type == 2){
            $filePath = "Public/avatar/";
        }

        $fileName = date("YmdHis",time());

        $randNum  = "";
        $numList  = "1234567890";
        for ($i = 1 ;$i < 10 ; $i++) {
            $j = mt_rand(0,9);
            $randNum .= $numList[$j];
        }

        $img_base = str_replace(" ","+",$img_base);
        $img_code = base64_decode($img_base);
        $file     = $filePath.$fileName."-".$randNum.".png";
        file_put_contents($file,$img_code);
        return $file;
    }

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