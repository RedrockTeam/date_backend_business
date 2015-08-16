<?php
namespace Api\Controller;
use Api\Model\DiscoveryModel;
use Think\Controller;

class DiscoverController extends BaseController {
    const TITLE = 10;//标题长度
    const CAPTION = 10;//副标题
    const PLACE = 10;//活动地点

    //发布发现
    public function createDiscovery() {
        $input = I('post.');
        $data = [
            'title' => $input['title'],
            'caption' => $input['caption'],
            'picture' => $input['picture'],//todo 图片给地址?
            'place' => $input['place'],
            'time' => $input['time'],
            'praise' => 0,
            'status' => 1
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
    public function discoverlist() {
        $page = I('post.page');
        $discover = new DiscoveryModel();
        $data = $discover->getDiscoverList($page);
        $this->ajaxReturn([
            'status' => 0,
            'info' => '成功',
            'data' => $data
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