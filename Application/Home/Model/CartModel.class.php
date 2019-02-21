<?php
namespace Home\Model;
use Think\Model;
class CartModel extends Model
{
	protected $insertFields = array('goods_id','goods_attr_id','goods_number');
	protected $updateFields = array('id','goods_id','goods_attr_id','goods_number');
	protected $_validate = array(
		array('goods_id', 'require', '商品不能为空！', 1),
		array('goods_number', 'require', '购买数量不能为空!', 1),
		array('goods_number', 'checkGoodsNumber', '购买数量超出库存量！', 1, 'callback'),
	);

	//检测购买商品是否超出库存量
    function checkGoodsNumber(){
        //获取商品属性
        $goods_attr_id = I("post.goods_attr_id",'');
        if($goods_attr_id!=="") {
            sort($goods_attr_id, SORT_NUMERIC);
            $goods_attr_id = implode(',', $goods_attr_id);
        }


        $gn_model = D('Admin/goods_number');
        $gn = $gn_model->field('goods_number')->where(array(
            'goods_id' => array('eq',I('post.goods_id')),
            'goods_attr_id' => array('eq',$goods_attr_id)
        ))->find();

        return $gn['goods_number'] > I('post.goods_number');
    }

    public function add()
    {
        if($this->goods_attr_id) {
            sort($this->goods_attr_id, SORT_NUMERIC);
            $this->goods_attr_id = implode(',', $this->goods_attr_id);
        }else{
            $this->goods_attr_id = "";
        }


        //如果登录了，把购物车数据存到数据库
        if(session('member')){
            //先判断购物车中是否已经有该商品，有的话直接追加购买数量
            $has = $this->where(array(
                'goods_id'=>$this->goods_id,
                'goods_attr_id'=>$this->goods_attr_id,
                'member_id'=>session('mid')
            ))->select();


            if($has){
                $flag = $this->where(array(
                    'goods_id'=>$this->goods_id,
                    'goods_attr_id'=>$this->goods_attr_id,
                    'member_id'=>session('mid')
                ))->setInc('goods_number',$this->goods_number);
            }
            else{
                $flag = parent::add(array(
                    'goods_id'=>$this->goods_id,
                    'goods_number'=>$this->goods_number,
                    'goods_attr_id'=>$this->goods_attr_id,
                    'member_id'=>session('mid')

                ));
            }


            if($flag)
                return True;
        }

        //如果没有登录，将购物车存在cookie中
        else{
            //先从cookie中取出购物车数组
            $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

            $key = $this->goods_id . '-' . $this->goods_attr_id;

            //先判断购物车中是否已经有该商品，有的话直接追加购买数量
            if($cart[$key]){
                $cart[$key] += $this->goods_number;
            }else{
                $cart[$key] = $this->goods_number;
            }

            setcookie('cart',serialize($cart),time()+86400,'/');

            return True;


        }

    }


    /****************将cookie中的购物车迁移到数据库*******************/
    public function moveCart(){
        //先从cookie中取出购物车数组
        $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

        if($cart){

            foreach ($cart as $k => $v){

                $gid_gaid = explode('-',$k);
                //先判断购物车中是否已经有该商品，有的话直接追加购买数量
                $has = $this->where(array(
                    'goods_id'=>$gid_gaid[0],
                    'goods_attr_id'=>$gid_gaid[1],
                    'member_id'=>session('mid')
                ))->select();

                if($has){
                    $flag = $this->where(array(
                        'goods_id'=>$gid_gaid[0],
                        'goods_attr_id'=>$gid_gaid[1],
                        'member_id'=>session('mid')
                    ))->setInc('goods_number',$v);
                } else{
                    $flag = parent::add(array(
                        'goods_id'=>$gid_gaid[0],
                        'goods_number'=>$v,
                        'goods_attr_id'=>$gid_gaid[1],
                        'member_id'=>session('mid')

                    ));
                }

                //清除cookie中的购物车
                setcookie('cart','',time()-1,'/');

            }



    }

    }


    /************取出购物车清单数据*************/
    public function cart_list(){

        //如果登录了
        if(session('member')){

            $data = $this->where(array('member_id'=>array('eq',session('mid'))))->select();




        }

        else{
            $cat = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
            //把数组整理成跟上面一样
            $data = array();
            foreach($cat as $k => $v){
                $gid_gaid = explode('-',$k);
                $_data['goods_id'] = $gid_gaid[0];
                $_data['goods_attr_id'] = $gid_gaid[1];
                $_data['goods_number'] = $v;

                $data[] = $_data;
            }
        }

        //完善数据
        foreach($data as $k => &$v){
            $goods_model = D('Admin/goods');
            //添加购买价格
            $v['price'] = $goods_model->getTruePrice($v['goods_id']);
            //获取logo,和商品名称
            $info = $goods_model->field('goods_name,mid_logo')->where(array('id'=>array('eq',$v['goods_id'])))->find();
            $v['goods_name'] = $info['goods_name'];
            $v['logo'] = $info['mid_logo'];

            if($v['goods_attr_id']){

                $gaid = explode(',',$v['goods_attr_id']);
                $ga_model = D('Admin/goods_attr');

                foreach ($gaid as $_k => $_v) {
                    $attr = $ga_model->alias('a')->field('a.attr_value,b.attr_name')
                        ->join('LEFT JOIN __ATTRIBUTE__ b on b.id=a.attr_id')
                        ->where(array('a.id'=>array('eq',$_v)))
                        ->find();

                    $v['attr'][] = $attr;
                }




            }



        }


        return $data;





    }


    //清除购物车
    public function clear(){
        $this->where(array(
            'member_id'=>array('eq',session('mid'))
        ))->delete();

    }
}