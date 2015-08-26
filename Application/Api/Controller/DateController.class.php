<?php
namespace Api\Controller;
use Api\Model\ApplyModel;
use Api\Model\CollectionModel;
use Api\Model\CommentModel;
use Api\Model\DateModel;
use Api\Model\UsersModel;
use Think\Controller;

class DateController extends BaseController {

    //获取约详情
    public function detailDate() {
        $date_id = I('post.date_id');
        $uid = I('post.uid');
        $date = new DateModel();
        $data = $date->detaildate($date_id, $uid);
        $comment = new CommentModel();
        $data['date_comment'] = $comment->getComment(['date_id' => $date_id, $page = 1]);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //收藏约
    public function collectDate() {
        $input = I('post.');
        if(!is_numeric($input['uid']) || !is_numeric($input['date_id'])) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '非法数据'
            ]);
        }
        $collection = new CollectionModel();
        if($collection->addCollection($input)) {
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功'
            ]);
        } else {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你已经收藏过此约会'
            ]);
        }

    }

    //发布约
    public function createDate() {

    }

    //报名约
    public function applyDate() {
        $input = I('post.');
        $result = $this->checkCondition($input['date_id'], $input['uid']);
        switch($result) {
            case 1:
                $status = 0;
                $info = '成功';
                $apply = new ApplyModel();
                $data = [
                    'date_id' => $input['date_id'],
                    'user_id' => $input['uid'],
                    'time' => time(),
                    'status' => 2
                ];
                $apply->add($data);
                M('date')->where(['date_id' => $input['date_id']])->setInc('apply_num');
                break;
            case 2:
                $status = 1;
                $info = '该约会已过期';
                break;
            case 3:
                $status = 1;
                $info = '约会人数已满';
                break;
            case 4:
                $status = 1;
                $info = '不符合性别限制';
                break;
            case 5:
                $status = 1;
                $info = '不符合学校限制';
                break;
            case 6:
                $status = -3;
                $info = '商家不能发布约, 只能发布发现';
                break;
            default:
                $status = 1001;
                $info = '系统开小差了';
                break;
        }
        $this->ajaxReturn([
            'status' => $status,
            'info' => $info
        ]);
    }

    //点赞约
    public function praiseDate() {
        $input = I('post.');
        $map = [
            'date_id' => $input['daet_id'],
            'user_id' => $input['uid']
        ];
        if(M('date_praise')->where($map)->count()) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你已经点赞过该约!'
            ]);
        } else {
            M('date_praise')->add($map);
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功!'
            ]);
        }
    }
    //取消点赞约
    public function delPraiseDate() {
        $input = I('post.');
        $map = [
            'date_id' => $input['daet_id'],
            'user_id' => $input['uid']
        ];
        if(!M('date_praise')->where($map)->count()) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你没有赞过该约!'
            ]);
        } else {
            M('date_praise')->where($map)->delete();
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功!'
            ]);
        }
    }
    //评论约
    public function commentDate() {
        $input = I('post.');
        if($input['content'] == null || $input['content'] == '') {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '评论内容不能为空'
            ]);
        }
        $data = [
            'user_id' => $input['uid'],
            'date_id' => $input['date_id'],
            'content' => $input['content'],
            'time'    => time(),
            'father_id' => $input['father_id']? $input['father_id']:0,
            'status' => 1
        ];
        M('comment')->add($data);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功'
        ]);
    }

    private function checkCondition($date_id, $uid) {
        $date = new DateModel();
        $date_info = $date->dateLimit($date_id);
        $user = new UsersModel();
        $user_info = $user->getUserLimitInfo($uid);
        //过期检查2
        if(time() > $date_info['create_time'] || $date_info['date_status'] != 2) {
            return 2;
        }
        //约会人数满员3
        if($date_info['promise_num'] >= $date_info['people_limit']) {
            return 3;
        }
        //性别检查4
        if($user_info['gender'] != 0 && $user_info['gender'] != $date_info['gender_limit']) {
            return 4;
        }
        //学校检查5
        if($user_info['school_id'] == null || $user_info['school_id'] == '') {
            return 5;
        }
        if($date_info['school_limit'] != null) {
            foreach($date_info['school_limit'] as list($v)){
                if($v == $user_info['school_id']) {
                    return 5;
                }
            }
        }
        //检查用户角色
        if($user_info['role_id'] > 2) {
            return 6;
        }
        return 1;
    }

}