<?php
namespace Api\Controller;
use Api\Model\ApplyModel;
use Api\Model\CollectionModel;
use Api\Model\CommentModel;
use Api\Model\DateModel;
use Api\Model\UsersModel;
use Think\Controller;

class DatePublicController extends Controller {

    //获取约详情
    public function detailDate() {
        $date_id = I('post.date_id');
        $uid = I('post.uid');
        $date = new DateModel();
        $data = $date->detaildate($date_id, $uid);
        $comment = new CommentModel();
        $data['date_comment'] = $comment->getComment(['date_id' => $date_id, 'page' => 1]);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }



    //获取评论
    public function getComment() {
        $input =  I('post.');
        $comment = new CommentModel();
        $data = $comment->getComment(['date_id' => $input['date_id'], 'page' => $input['page']]);
        $this->ajaxReturn([
            'status' => 0,
            'info'   => '成功',
            'data'   => $data
        ]);
    }


    //搜索约
    public function search() {
        $content = explode(' ', I('post.content'));
        $i = 0;
        foreach($content as $v) {
            $search[] = '%'.$v.'%';
            $i++;
            if($i == 3) {
                break;
            }
        }
        $map = [
            'date.title' => ['LIKE', $search, 'or'],
            'date.content' => ['LIKE', $search, 'or'],
            '_logic' => 'or'
        ];
        $data = M('date')->where($map)
                         ->join('join users on date.user_id = users.id')
                         ->join('join date_type on date.date_type = date_type.id')
                         ->group('date.id')
                         ->field('users.id as uid, date.title, date.content, date_type.type as date_type, date.cost_type, date.date_place, date.praise, date.id as date_id, users.avatar, users.nickname, users.signature, users.gender, users.grade, users.role_id, date.date_time, date.create_time, date.status as date_status, date.apply_num, date.comment_num')
                         ->limit(10)
                         ->select();
        foreach($data as &$value) {
            if(M('date_praise')->where(['user_id' => I('post.uid'), 'date_id' => $value['date_id']])->count()) {
                $value['praise_status'] = 1;
            } else {
                $value['praise_status'] = 0;
            }
        }
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data?$data:[]
        ]);
    }

    //热搜关键词
    public function hotsearch() {
        $data = M('date_type')->order('rand() asc')->getField('type');
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }


}