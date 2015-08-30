<?php
namespace Api\Controller;
use Think\Controller;
class BaseController extends Controller {
    private $appKey    = 'c9kqb3rdk5yrj';
    private $appSecret = 'erijfn2nNwAG';

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
        $weekday = $dayNum % 7;
        $weekday = intval($weekday);
        if ($weekday != 0){
        } else {
            $weekday = 7 ;
        }
        return $weekday == 7 || $weekday == 6 ? true : false;
    }

    protected function chatUpdate ($id) {
        $info  = M('users')->where("id = '$id'")->find();
        $param = [
            'userId' => $id,
            'name'   => $info ['nickname'],
            'portraitUri' => $info ['avatar']
        ];
        $res = $this->curl($param,"2");

        $code = $res ['code'];
        return $code == 200 ? true : false;
    }
    /**
     * @return array
     */
    protected function httpHeader () {
        $nonce =mt_rand();
        $timeStamp = time();
        $sign = sha1($this->appSecret.$nonce.$timeStamp);
        $return = [
            'RC-App-Key:'.$this->appKey,
            'RC-Nonce:'.$nonce,
            'RC-Timestamp:'.$timeStamp,
            'RC-Signature:'.$sign,
        ];
        return $return;
    }

    /**
     * @param $params
     * @return int|mixed
     */
    protected function curl($params,$type = "1") {
        if ($type == "1") {
            $action = "https://api.cn.ronghub.com/user/getToken.json";
        } else {
            $action = "https://api.cn.ronghub.com/user/refresh.json";
        }
        $httpHeader = $this->httpHeader();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildQuery($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if (false === $res) {
            $res =  curl_errno($ch);
        }
        curl_close($ch);
        $res = json_decode($res,true);
        return $res;
    }

    /**
     * @param $formData
     * @param string $numericPrefix
     * @param string $argSeparator
     * @param string $prefixKey
     * @return string
     */
    protected function buildQuery($formData, $numericPrefix = '', $argSeparator = '&', $prefixKey = '') {
        $str = '';
        foreach ($formData as $key => $val) {
            if (!is_array($val)) {
                $str .= $argSeparator;
                if ($prefixKey === '') {
                    if (is_int($key)) {
                        $str .= $numericPrefix;
                    }
                    $str .= urlencode($key) . '=' . urlencode($val);
                } else {
                    $str .= urlencode($prefixKey) . '=' . urlencode($val);
                }
            } else {
                if ($prefixKey == '') {
                    $prefixKey .= $key;
                }
                if (is_array($val[0])) {
                    $arr = array();
                    $arr[$key] = $val[0];
                    $str .= $argSeparator . http_build_query($arr);
                } else {
                    $str .= $argSeparator . $this->buildQuery($val, $numericPrefix, $argSeparator, $prefixKey);
                }
                $prefixKey = '';
            }
        }
        return substr($str, strlen($argSeparator));
    }

    /**
     * @param $to_user string
     * @param $type string 0 报名, 1接受, 2拒绝
     * @param $date_id int
     * @return bool
     * 用于消息的创建，目标用户，类型，对应约id
     */
    protected function createMessage ($to_user,$type,$date_id) {
        $save = [
            'to_user' => $to_user,
            'm_type'  => $type,
            'date_id' => $date_id,
            'm_time'  => time()
        ];
        M('message')->add($save);
        return true;
    }


}