<?php
namespace Admin\Controller;
use Think\Controller;
class ManageController extends BaseController {
    public function index() {
        $data = M('admin')->where(['admin.id' => session('admin_id')])->find();
        $this->assign('data', $data);
        $this->display();
    }

    //添加发现
    public function add() {
        $this->display();
    }

    //添加发现
    public function addDiscovery() {
        $data = I('post.');

        if(mb_strlen($data['title'])<1) {
            $this->error('标题不能为空');
        }
        if(mb_strlen($data['caption'])<1) {
            $this->error('副标题不能为空');
        }
        if(mb_strlen($data['place'])<1) {
            $this->error('地点不能为空');
        }
        if(mb_strlen($data['time'])<1) {
            $this->error('时间不能为空');
        }
        if(mb_strlen($data['content'])<1) {
            $this->error('内容不能为空');
        }
        $upload = new \Think\Upload(c('upload'));// 实例化上传类
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            foreach($info as $file){
                echo $file['savepath'].$file['savename'];
                $data['picture'] = 'http://:'.$_SERVER['HTTP_HOST'].__APP__.'/Public/upload/'.$file['savepath'].$file['savename'];
            }
        }
        $data['time'] = strtotime($data['time']);
        $data['praise'] = 0;
        $data['status'] = 1;
        $data['user_id'] = 1;
        M('discover')->add($data);

        $this->success('成功');

    }

    //审核发现
    public function judge() {
        $data = M('discover')->where(['discover.status' => 2])
                             ->join('JOIN users ON discover.user_id = users.id')
                             ->field(['discover.id as discover_id', 'users.id as uid', 'users.nickname', 'discover.title', 'discover.caption', 'discover.content', 'discover.picture'])
                             ->select();
        $this->assign('data', $data);
        $this->display();
    }

    //通过
    public function pass() {
        $id = I('post.id');
        M('discover')->where(['id' => $id])->save(['status' => 1]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }

    //不通过
    public function nopass() {
        $id = I('post.id');
        M('discover')->where(['id' => $id])->save(['status' => 3]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }
}