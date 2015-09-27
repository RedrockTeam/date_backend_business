<?php
namespace Admin\Controller;
use Think\Controller;
class BussinessController extends BaseController {
    public function index() {
        $page = I('get.p');
        $users = M('users');
        $data = $users->page($page, 30)
                          ->where(['role_id' => 3])
                          ->field('id as uid, nickname, gender, phone, status')
                          ->select();
        $count      = $users->where(['role_id' => 3])->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('data', $data);
        $this->display();
    }

    //通过
    public function pass() {
        $id = I('post.id');
        M('users')->where(['id' => $id])->save(['status' => 1]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }

    //不通过
    public function nopass() {
        $id = I('post.id');
        M('users')->where(['id' => $id])->save(['status' => 0]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }

    //修改信息
    public function save() {
        $id = I('post.id');
        $phone = I('post.phone');
        M('users')->where(['id' => $id])->save(['phone' => $phone]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功'
        ]);
    }

    public function search() {
        $input = I('post.');
        switch($input['search_id']) {
            case 1:
                $map['nickname'] = ['like', '%'.$input['content'].'%'];
                break;
            case 2:
                $map['phone'] = ['like', '%'.$input['content'].'%'];
                break;
            default:
                $map['id'] = $input['content'];
                break;
        }
        $map['role_id'] = 3;
        $data = M('users')
            ->where($map)
            ->field('id as uid, nickname, gender, phone, status')
            ->select();
        $this->assign('data', $data);
        $this->display('index');
    }
}