<?php
namespace Admin\Controller;
use Think\Controller;
class DateManageController extends BaseController {

    public function index(){
        $page = I('get.page');
        $data = M('date')->page($page, 15)->select();
        $this->assign('data', $data);
        $this->display();
    }

    public function search() {
        $input = I('post.');
        switch($input['search_id']) {
            case 1:
                    $map['title'] = ['like', '%'.$input['content'].'%'];
                    break;
            case 2:
                    $map['content'] = ['like', '%'.$input['content'].'%'];
                    break;
            case 3:
                    $map['id'] = $input['content'];
                    break;
            default:
                    $map['id'] = $input['content'];
                    break;
        }
        $data = M('date')->where($map)->select();
        $this->assign('data', $data);
        $this->display('index');
    }

    public function freeze() {
        $date_id = I('post.date_id');
        M('date')->where(['id' => $date_id])->delete();
        M('apply')->where(['date_id' => $date_id])->delete();
        M('comment')->where(['date_id' => $date_id])->delete();
        M('date_limit')->where(['date_id' => $date_id])->delete();
        M('date_praise')->where(['date_id' => $date_id])->delete();
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }
}