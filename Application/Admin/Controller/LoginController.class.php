<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{
    public function login(){
        if(IS_POST){

            $model = D('admin');
            //接收表单并验证表单
            if($model->validate($model->_login_validate)->create(I('post.'),1)){
                if($model->login()){

                    $this->success('登录成功',U('Index/index'));
                    exit;
                }
            }
            $this->error($model->getError());
        }
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

    public function logout(){
        //清除session
        session(null);
        redirect(U('Login/login'));

    }
}