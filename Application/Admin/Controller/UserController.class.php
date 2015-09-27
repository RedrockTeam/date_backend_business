<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController
{
    public function index () {
        return $this->display();
    }

    //修改密码
    public function editPassword () {
        $password = I('post.password');
        if ($password == null) {
            $this->ajaxReturn([
                'status' => 403,
                'info'   => '密码不能为空',
            ]);
        }
        if (strlen($password) < 6) {
            $this->ajaxReturn([
                'status' => 403,
                'info'   => '密码太短',
            ]);
        }
        $hash = md5(sha1($password));
        M('admin')->where(['id' => session('admin_id')])->save(['password' => $hash]);
        $this->ajaxReturn([
            'status' => 200,
            'info'   => '成功',
        ]);
    }

    public function user_group () {
        $db_user = M('users');

        $count = $db_user->where("role_id != 3")->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $list = $db_user->where("role_id != 3")->page($_GET['p'].',15')->select();

        $this->assign([
            'page'     => $show,
            'list'     => $list
        ]);

        $this->display();
    }

    public function user_info () {
        $id = I('post.userId');


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
            ->where()
            ->select();
        $this->assign('list', $data);
        $this->display('user_group');
    }
}