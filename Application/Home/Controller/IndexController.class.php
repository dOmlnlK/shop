<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController
{
    public function index()
    {


        //取出促销商品
        $goods_model = D('Admin/goods');
        $pro_goods = $goods_model->getPromoteGoods();
        //取出热销商品
        $hot_goods = $goods_model->getHotGoods();
        //取出精品商品
        $best_goods = $goods_model->getBestGoods();
        //取出新品
        $new_goods = $goods_model->getNewGoods();
        //获取推荐楼层分类
        $cat_model = D('Admin/category');
        $floor_data = $cat_model->getFloorData();

        $this->assign(array(
            '_show_nav' => 1,
            'pro_goods' =>$pro_goods,
            'hot_goods' => $hot_goods,
            'best_goods'=>$best_goods,
            'new_goods' => $new_goods,
            'floor_data' => $floor_data,
            '_page_title' => '京西商城'
        ));

        $this->display();
    }

    public function test(){
        $model = D('Admin/category');
        dump($model->getParentCid(26));

        $this->display();
    }

    public function goods(){

        //当前商品id
        $id = I('get.id');
        //获取当前商品所属分类
        $goods_model = D('Admin/goods');
        $goods_info =$goods_model->find($id);
        $cat_model =  D('Admin/category');
        $cids = $cat_model->getParentCid($goods_info['cat_id']);
        $cat_list = $cat_model->where(array(
            'id' => array('in',$cids)
        ))->select();

        /****************取出商品相关属性*********************/
        $ga_model = D('Admin/goods_attr');
        //唯一属性
        $uniAttr = $ga_model->alias('a')->field('a.attr_value,b.attr_name')->
            join('LEFT JOIN __ATTRIBUTE__ b on a.attr_id=b.id')
            ->where(array(
                'a.goods_id' => array('eq',$id),
                'b.attr_type' => array('eq','唯一')

        ))->select();

        //可选属性
        $_mulAttr = $ga_model->alias('a')->field('a.attr_value,b.attr_name,a.id')->
        join('LEFT JOIN __ATTRIBUTE__ b on a.attr_id=b.id')
            ->where(array(
                'a.goods_id' => array('eq',$id),
                'b.attr_type' => array('eq','可选')

            ))->select();


        //整理可选属性数据
        $mulAttr =array();
        foreach ($_mulAttr as $k => $v){
            $mulAttr[$v['attr_name']][$v['id']] = $v['attr_value'];

        }

        /****************取出会员价格***********************/
        $mp_model = D('Admin/member_price');
        $mp = $mp_model->alias('a')->field('a.price,b.level_name')->
        join('LEFT JOIN __MEMBER_LEVEL__ b on a.level_id=b.id')
            ->where(array(
                'a.goods_id' => array('eq',$id),

            ))->select();


        $ic = C('IMAGE_CONFIG');
        $viewPath = $ic['viewPath'];
        $this->assign(array(
            '_show_nav' => 0,
            'cat_list' => $cat_list,
            'goods_info' => $goods_info,
            "viewPath" => $viewPath,
            'uniAttr' => $uniAttr,
            'mulAttr' => $mulAttr,
            'mp' => $mp,
            '_page_title' => '商品信息'
        ));
        $this->display();
    }


    public function getHistory(){
        //当前访问商品的id
        $id = I('get.id');
        //从cookie取出浏览历史商品
        $data = isset($_COOKIE['view_history'])?unserialize($_COOKIE['view_history']):array();
        //把当前浏览商品插入浏览历史前面
        array_unshift($data,$id);
        //去重
        array_unique($data);
        //数组只保存六个
        if(count($data)>6)
            $data = array_slice($data,0,6);

        //更新cookie
        setcookie('view_history',serialize($data),time()+30*86400,'/');

        //根据商品id取出商品信息
        $ids = implode(',',$data);
        $goods_model = D('Admin/goods');
        $viewed_goods = $goods_model -> where(array(
            'id' => array('in',$data)
        ))->order("FIELD(id,$ids)")->select();

        echo json_encode($viewed_goods);
    }

    public function ajaxGetTruePrice(){
        $id = I('get.id');
        $true_price = D('Admin/goods')->getTruePrice($id);
        echo $true_price;

    }

}