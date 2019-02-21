<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
    {
        //调用一下父类的构造方法
        parent::__construct();

        if(!session('user')){
            $this -> error('请先登录',U('Login/login'));
        }

        //所有admin都可以访问后台主页
        if(CONTROLLER_NAME=='Index')
            return true;

        $pri_model = D('privilege');
        if(!$pri_model->checkPri()){
            $this -> error('无权访问当前页面！');
        }
    }


}