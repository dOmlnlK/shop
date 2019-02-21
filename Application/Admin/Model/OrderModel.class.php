<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model
{
	protected $insertFields = array('shr_name','shr_tel','shr_province','shr_city','shr_area','shr_address');
	protected $updateFields = array('id','shr_name','shr_tel','shr_province','shr_city','shr_area','shr_address');
	protected $_validate = array(
		array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
        array('shr_tel', 'require', '收货人联系方式不能为空！', 1, 'regex', 3),
        array('shr_province', 'require', '收货省份不能为空！', 1, 'regex', 3),
        array('shr_city', 'require', '收货城市不能为空！', 1, 'regex', 3),
        array('shr_area', 'require', '收货区域不能为空！', 1, 'regex', 3),
        array('shr_address', 'require', '收货详细地址不能为空！', 1, 'regex', 3),

	);


	//创建订单前需做一些验证
	protected function _before_insert(&$data, &$options)
    {
       //加锁解决并发下单发生错误
        $this->fp = fopen('./order.lock','r');
        flock($this->fp,LOCK_EX);

        //取出购物车商品数据
        $cart_model = D('Home/cart');
        $options['goods'] = $goods = $cart_model->cart_list();

        //计算总价
        $total_price = 0;
        foreach($goods as $k => $v){

            //判断库存量
            $gn_model = D('Admin/goods_number');
            $gn = $gn_model->field('goods_number')->where(array(
                'goods_id'=>array('eq',$v['goods_id']),
                'goods_attr_id'=>array('eq',$v['goods_attr_id'])
            ))->find();


            if($gn['goods_number'] < $v['goods_number']){
                $this -> error = '购买商品超出库存量！';
                return false;
            }

            $total_price += $v['price'];

        }

        $data['addtime'] = time();
        $data['total_price'] = $total_price;
        $data['member_id'] = session('mid');

        //开启事务
        $this->startTrans();
    }


    //创建订单后
    protected function _after_insert($data, $options)
    {
        //添加订单的购买商品信息
        $og_model = D('order_goods');
        $goods = $options['goods'];

        $gn_model = D('Admin/goods_number');
        foreach ($goods as $k => $v){

            $ret = $og_model->add(array(
                'order_id' => $data['id'],
                'goods_id' => $v['goods_id'],
                'goods_attr_id' => $v['goods_attr_id'],
                'goods_number' => $v['goods_number'],
                'price' => $v["price"]
            ));

            //添加失败，回滚事务
            if($ret===False){
                $this->rollback();
            }

            //减少库存量
            $ret = $gn_model -> where(array(
                'goods_id'=>array('eq',$v['goods_id']),
                'goods_attr_id'=>array('eq',$v['goods_attr_id'])
            )) -> setDec('goods_number',$v['goods_number']);

            //添加失败，回滚事务
            if($ret===False){
                $this->rollback();
            }

        }

        $this->commit();
        //清除购物车
        $cart_model = D('Home/cart');
        $cart_model->clear();

        //解锁
        flock($this->fp,LOCK_UN);
        fclose($this->fp);

    }



    /**
     * 设置为已支付的状态
     */
    public function updateOrder($orderId)
    {
        /**
         * ************　更新定单的支付状态　＊＊＊＊＊＊＊＊＊＊＊＊＊／
         */
        $this->where(array(
            'id' => array('eq', $orderId),
        ))->save(array(
            'pay_status' => '是',
            'pay_time' => time(),
        ));
        /************ 更新会员积分 *******************/
        $tp = $this->field('total_price,member_id')->find($orderId);
        $memberModel = M('member');  // 因为如果用D生成模型，那么在修改字段时会调用这个模型的_before_update方法，但现在这个功能不需要调用这个这个方法，所以这里使用M生成父类模型这样就不会调用_before_update了
        $memberModel->where(array(
            'id' => array('eq', $tp['member_id']),
        ))->setInc('jifen', $tp['total_price']);
    }


    public function search($pageSize = 20)
    {
        /**************************************** 搜索 ****************************************/
        $where = array();
        //未支付订单
        $noPatOrders = $this->where(array(
            'member_id' => array('eq',session('mid')),
            "pay_status" => array('eq','否')
        ))->select();


        /************************************* 翻页 ****************************************/
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /************************************** 取数据 ******************************************/
        $data['data'] =
            $this->
            alias('a')->field('a.id,a.shr_name,a.total_price,a.addtime,a.pay_status,GROUP_CONCAT(c.mid_logo) logo')->
                join('LEFT JOIN __ORDER_GOODS__ b on a.id=b.order_id
                      LEFT JOIN __GOODS__ c on b.goods_id=c.id')->
            where($where)->group('a.id')->
            limit($page->firstRow.','.$page->listRows)->
            select();

        $data['noPayCount'] = count($noPatOrders);
        return $data;
    }

}