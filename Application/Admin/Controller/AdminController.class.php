<?php
/**
 * Created by PhpStorm.
 * User: DeadSoul
 * Date: 2015/9/25
 * Time: 18:36
 */

namespace Admin\Controller;
use Think\Controller;

class AdminController extends BaseController {
    public function admin_add () {
        if (IS_POST) {
            $input = I('post.');
            $map = [
                'username' => $input['username']
            ];
            $admin = M('admin');
            $data = $admin->where($map)->find();
            if ($data) {
                $this->error('用户已经存在');
            } else {
                $add = [
                    'username' => $input['username'],
                    'password' => md5(sha1($input['password']))
                ];
                $admin->add($add);
                $this->success('添加成功');
            }
        } else {
            $this->display();
        }
    }

    public function admin_list () {
        $list = M('admin')->select();

        $this->assign('list',$list);
        $this->display();
    }

    public function admin_del ($id) {
        M('admin')->where("id = '$id'")->delete();
        $this->success('删除成功');
    }
}