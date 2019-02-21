<?php
namespace Home\Controller;
use Think\Controller;


class MyController extends Controller
{
    public function __construct()
    {
        if(!session('member')){

            session('returnUrl',U('My/'.ACTION_NAME));
            redirect(U('Member/login'));
            exit;
        }

        parent::__construct();
    }


    public function order(){

        $order_model = D('Admin/order');
        $data = $order_model->search();

        $this->assign(array(
            'data' => $data
        ));

        $this->display();
    }

}