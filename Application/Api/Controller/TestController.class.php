<?php
namespace Api\Controller;
use Think\Controller;
class TestController extends Controller
{
    public function login()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                'uid' => 1, //用户id
                'avatar' => "http://dsfsdaa", //用户头像
                'nickname' => "刘慧芝减肥成功了吗?", //用户昵称
                'signature' => "23333333", //用户个性签名
                'gender' => 1, //用户性别1男2女
                'role_id' => 1, //用户身份
                'token' => 'asdfghjkl', //token

            ]
        ]);
    }

    public function school()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                [
                    'school_id' => 1,
                    'school_name' => "重邮"
                ],
                [
                    'school_id' => 2,
                    'school_name' => "重大"
                ],
                [
                    'school_id' => 3,
                    'school_name' => "家里蹲大"
                ]
            ]
        ]);
    }

    public function info()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                'uid' => 1,
                'nickname' => '刘慧芝又肥了',
                'avatar' => 'http://',
                'realname' => '',//真名, 获取自己信息才会出现
                'gender' => '1',
                'school' => '重邮',
                'signature' => '233333333',
                'hobby' => [
                    "吃",
                    "喝",
                    "嫖",
                    "赌"
                ],
                'role_id' => 1,//身份
                'charm' => 100 //魅力值
            ]
        ]);
    }

    public function collection()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                'date_id' => 3,
                'title' => '吃饭',
                'uid' => 1,
                'avatar' => 'http://',
                'nickname' => '刘慧芝又肥了',
                'date_status' => 1
            ]
        ]);
    }

    public function getletter()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                'from' => 1,
                'from_avatar' => 'http://',
                'to' => 2,
                'to_avatar' => 'http://',
                'content' => 'sb报名了你的约',
                'time' => 11111111111,
            ]
        ]);
    }

    public function postletter()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                'from' => 1,
                'to' => 2,
                'content' => '你报名了你的约',
                'time' => 11111111111,
            ]
        ]);
    }

    public function mydatelist()
    {
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => [
                [
                    'date_id' => 2,
                    'uid' => 1,
                    'avatar' => 'http://',
                    'title' => 'haha',
                    'time' => 11111111111,
                    'date_status' => 1
                ]
            ]
        ]);
    }

    public function joineddatelist() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                [
                    'date_id' => 2,
                    'uid' => 2,
                    'avatar' => 'http://',
                    'title' => 'haha',
                    'time' => 11111111111,
                    'date_status' => 1
                ]
            ]
        ]);
    }

    public function care() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                [
                    'uid' => '2',
                    'nickname' => 'haha',
                    'avatar' => 'http://',
                    'signature' => 'sbsbs',
                    'charm' => 100,
                    'role_id' => 1
                ]
            ]
        ]);
    }

    public function careme() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                [
                    'uid' => '2',
                    'nickname' => 'haha',
                    'avatar' => 'http://',
                    'signature' => 'sbsbs',
                    'charm' => 100,
                    'role_id' => 1
                ]
            ]
        ]);
    }

    public function userverify() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
				'info' => "成功",
            ]
        ]);
    }


    public function editavatar() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
                'info' => "成功",
            ]
        ]);
    }

    public function editsignature() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
                'info' => "成功",
            ]
        ]);
    }

    public function edithobby() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
                'info' => "成功",
            ]
        ]);
    }

    public function editpassword() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
                'info' => "成功",
            ]
        ]);
    }

    public function datelist() {
        $this->ajaxReturn([
                'status' => 0,
                'info' => "成功",
                'data' => [
                    [
                        'date_id' => 2,
				        'title' => 'adaf2333',
				        'content' => 'fds33333', //详情
				        'created_time' => 11111111111,//发布时间
				        'date_type' => 1,//约会类型
				        'praise' => 30,//点赞
				        'comment_num' => 20,//评论数量
				        'apply_num' => 51,//报名人数
				        'uid' => 1,
                        'praise' => 2,
				        'nickname' => '刘慧芝又肥了',
				        'signature' => '不要你管',
				        'avatar' => 'http://',
				        'gender' => 1,
                        'grade' => 2013,
				        'role_id' => 1,

                    ]
                ]
        ]);
    }

    public function detaildate() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                'status' => 0,
                'info' => "成功",
                'data' => [
                        'date_id' => 2,
                        'title' => 'adaf2333',
				        'content' => 'fds33333', //详情
				        'created_time' => 11111111111,//发布时间
				        'date_type' => 1,//约会类型
				        'date_place' => '潘阿姨',//
				        'date_time' => 11111111111,//约会时间
				        'cost_type' => 1,//消费方式
				        'people_limit' => 1,//人数限制
				        'gender_limit' => 0,
				        'school_limit' => [['school_id' =>1, 'school_name' =>"重邮"]],
				        'date_comment' =>[
				        [
                            'uid' => 1,
                            'avatar' => 'http://',
                            'nickname' => '刘慧芝又肥了',
                            'comment_id' => 8,
                            'content' => "i'm sb",
                            'time' => 111111111,
                            'reply' => [
				            [
                                'uid' => 1,
                                'avatar' => 'http://',
                                'nickname' => '刘慧芝又肥了',
                                'content' => 'asdfdfa',
                                'time' => 111111111,
                            ],
				            ]
				        ]
				        ],
				        'date_status' => 1,
				        'uid' => 1,
				        'avatar' => 'http://',
				        'gender' => 1,
				        'role_id' => 1,
                    
                ],
            ]
        ]);
    }

    public function createdate() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
        ]);
    }

    public function applydate() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
        ]);
    }

    public function collectdate() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
        ]);
    }

    public function discoverlist() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                [
                    
				     'discover_id' => 1,
				     'discover_title' => 'go',
				     'discover_caption' => 'lai', //副标题
				     'discover_picture' => 'http://',//图片
				    
                ]
            ]
        ]);
    }

    public function detaildiscover() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
            'data' => [
                     'discover_id' => 1,
				     'discover_title' => 'go',
				     'discover_caption' => 'lai', //副标题
                     'discover_picture' => 'http://',//图片
				     'discover_time' => 11111111111,//活动时间
				     'discovet_place' => '潘阿姨',//活动地点
				     'discover_praise' => 44,//点赞数
				     'discover_status' => 1,//发现状态
				     'discover_comment' => [
				         [
                             'uid' => 1,
                             'avatar' => 'http://',
                             'nickname' => '刘慧芝又肥了',
                             'comment_id' => 8,
                             'content' => "i'm sb",
                             'time' => 111111111,
                             'reply' => [
                                 [
                                     'uid' => 1,
                                     'avatar' => 'http://',
                                     'nickname' => '刘慧芝又肥了',
                                     'content' => 'asdfdfa',
                                     'time' => 111111111,
                                 ],
                             ]
				        ]
                    ] 

            ]
        ]);
    }

    public function applydiscover() {
        $this->ajaxReturn([
            'status'=> 0,
            'info' => '成功',
        ]);
    }

}
