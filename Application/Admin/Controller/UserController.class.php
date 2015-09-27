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

        $info = M('users')->where("id = $id")->find();

        $return = [
            'status' => '200',
            'info'   => 'success',
            'data'   => $info
        ];

        $this->ajaxReturn($return);
    }

    public function user_edit () {
        $Info = I('post.');
        $id = $Info['id'];
        $save = [
            'nickname' => $Info['nickname'],
            'signature'=> $Info['signature']
        ];
        M('users')->where("id = '$id'")->save($save);
        $this->success('成功');
    }
    public function user_ice ($id) {
        $info = M('users')->where("id = '$id'")->find();

        $status = $info ['status'];

        if ($status == '0') {
            $save ['status'] = 1;
        } else {
            $save ['status'] = 0;
        }

        M('users')->where("id = '$id'")->save($save);
        $this->success('成功');
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
        $data = M('users')
            ->where($map)
            ->where("role_id != 3")
            ->select();
        $this->assign('list', $data);
        $this->display('user_group');
    }

    public function realname_group () {
        $list = M('verify')->where("status = 0")->join("school ON school.id = verify.school_id")->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function user_pass ($id) {
        $info = M('verify')->where("user_id = '$id'")->find();
        $info ['status'] = "1";
        M('verify')->where("user_id = '$id'")->save($info);

        $save = [
            'realname' => $info ['realname'],
            'status'   => '2'
        ];

        M('users')->where("id = '$id'")->save($save);
        $this->success('审核通过');
    }

    public function user_false($id) {
        $save ['status'] = '2';
        M('verify')->where("user_id = '$id'")->save($save);
        $this->success('操作成功');
    }
}