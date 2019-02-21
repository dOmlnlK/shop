<?php
namespace Home\Controller;
use Think\Controller;

/*
 * 需要用导航栏的控制器直接继承该控制器即可
 * */
class NavController extends Controller
{
    public function __construct()
    {
//        调用父类构造方法
        parent::__construct();

        $cat_model = D('Admin/category');
        $nav_data = $cat_model->getCatData();

        $this->assign(array(
            "nav_data"=>$nav_data
        ));

    }

}