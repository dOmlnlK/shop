<?php
namespace Home\Controller;
use Think\Controller;

class MemberController extends Controller
{
    public function regist()
    {
        if (IS_POST) {

            $model = D('Admin/member');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('注册成功！', U('login'));
                    exit;
                }
            }else{
                $this->error($model->getError());
            }

        }
        // 设置页面中的信息
        $this->assign(array(

            '_page_title' => '注册用户',
            '_page_btn_name' => '管理员列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();
    }


    //返回验证码
    public function checkCode(){
        $config =    array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    3,     // 验证码位数
            'useNoise'    =>    True, // 关闭验证码杂点

        );
        $Verify = new \Think\Verify($config);

        $Verify->entry();
    }


    public function login(){
        if(IS_POST){

            $model = D('Admin/member');
            //接收表单并验证表单
            if($model->validate($model->_login_validate)->create()){
                if($model->login()){
                    $jump = '/';
                    $returnUrl = session('returnUrl');
                    if($returnUrl)
                        $jump = $returnUrl;
                    session('returnUrl',null);
                    $this->success('登录成功',$jump);
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $this->assign(array(
            '_page_title' => '登录用户'
        ));
        $this->display();
    }

    public function logout(){
        session(null);
        redirect(U('Index/index'));
    }


    public function ajaxCkLogin(){
        if(session('member')){
            $data = array(
                'status' => 1,
                'member' => session('member')
            );
            echo json_encode($data);
        }else{
            $data = array(
                'status' => 0,
            );
            echo  json_encode($data);
        }


    }

}