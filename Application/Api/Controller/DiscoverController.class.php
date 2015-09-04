<?php
namespace Api\Controller;
use Api\Model\DiscoveryModel;
use Think\Controller;

class DiscoverController extends BaseController {
    const TITLE = 10;//标题长度
    const CAPTION = 10;//副标题
    const PLACE = 10;//活动地点

    //发布发现
    public function createDiscover() {
        $input = I('post.');
        $data = [
            'title' => $input['title'],
            'caption' => $input['caption'],
            'picture' => $input['picture'],//todo 图片给地址?
            'place' => $input['place'],
            'time' => $input['time'],
            'cost_type' => $input['cost_type'],
            'user_id' => $input['uid'],
            'content' => $input['content'],
            'praise' => 0,
            'status' => 2
        ];
        if(!$this->checkData($data)) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '内容过长'
            ]);
        }
        $discover = new DiscoveryModel();
        $discover->add($data);

    }

    //获取发现列表
    public function discoverList() {
        $page = I('post.page');
        $discover = new DiscoveryModel();
        $data = $discover->getDiscoverList($page);
        foreach($data as &$value)
        if(M('discover_praise')->where(['discover_id' => $value['discover_id'], 'user_id' => I('post.uid')])->count()) {
            $data['praise_status'] = 1;
        } else {
            $data['praise_status'] = 0;
        }
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //获取发现详情
    public function detailDiscover() {
        $discover_id = I('post.discover_id');
        $discover = new DiscoveryModel();
        $data = $discover->getDiscover($discover_id);
        if(M('discover_praise')->where(['discover_id' => $discover_id, 'user_id' => I('post.uid')])->count()) {
            $data['praise_status'] = 1;
        } else {
            $data['praise_status'] = 0;
        }
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
        ]);
    }

    //参加发现
    public function applyDiscover() {
        $input = I('post.');
        $discover = new DiscoveryModel();
        if(!$discover->joinDiscover($input)) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '报名已结束'
            ]);
        } else {
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功'
            ]);
        }
    }

    //评论发现
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
            'id' => $input['discover_id'],
            'content' => $input['content'],
            'time'    => time(),
            'father_id' => $input['father_id']? $input['father_id']:0,
            'status' => 1
        ];
        M('discover_comment')->add($data);
        M('discover')->where(['id', $input['discover_id']])->setInc('comment_num');
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功'
        ]);
    }

    //点赞发现
    public function praiseDiscover() {
        $input = I('post.');
        $map = [
            'discover_id' => $input['discover_id'],
            'user_id' => $input['uid']
        ];
        if(M('discover_praise')->where($map)->count()) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你已经点赞过该约!'
            ]);
        } else {
            M('discover_praise')->add($map);
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功!'
            ]);
        }
    }
    //取消点赞发现
    public function delPraiseDiscover() {
        $input = I('post.');
        $map = [
            'discover_id' => $input['discover_id'],
            'user_id' => $input['uid']
        ];
        if(!M('discover_praise')->where($map)->count()) {
            $this->ajaxReturn([
                'status' => 1,
                'info' => '你没有赞过该发现!'
            ]);
        } else {
            M('discover_praise')->where($map)->delete();
            M('discover')->where(['discover_id' => $input['discover_id']])->setDec('praise');
            $this->ajaxReturn([
                'status' => 0,
                'info' => '成功!'
            ]);
        }
    }

    //搜索发现
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
            'discover.title' => ['LIKE', $search, 'or'],
//            'discover.content' => ['LIKE', $search, 'or'],
//            '_logic' => 'or'
        ];
        $data = M('discover')->where($map)
                             ->group('discover.id')
                             ->field('id as discover_id, title as discover_title, caption as discover_caption, picture as discover_picture, time as discover_time, praise as discover_praise, status as discover_status')
                             ->limit(10)
                             ->select();
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data?$data:[]
        ]);
    }

   private function checkData($data) {
        if(mb_strlen($data['title'], 'utf8') > self::TITLE){
            return false;
        }
        if(mb_strlen($data['caption'], 'utf8') > self::CAPTION){
            return false;
        }
        if(mb_strlen($data['place'], 'utf8') > self::PLACE){
            return false;
        }
        return true;
    }
}