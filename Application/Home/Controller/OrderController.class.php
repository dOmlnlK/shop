<?php
namespace Home\Controller;
use Think\Controller;


class OrderController extends Controller
{
    public function add()
    {
        if (IS_POST) {

            if (!session('member')) {
                session('returnUrl', U('Order/add'));
                $this->error('请先登录在进行下单！', U('Member/login'));
            }

            $order_model = D('Admin/order');
            if ($order_model->create(I('post.'), 1)) {
                if ($orderId = $order_model->add()) {
                    $this->success('下单成功!', U('order_success?order_id='.$orderId));
                    exit;
                } else {
                    $this->error($order_model->getError());
                }
            } else {

                $this->error($order_model->getError());
            }

        }

        $cart_model = D('cart');
        $data = $cart_model->cart_list();

        $this->assign(array(
            'data' => $data,
            '_page_title' => '订单支付'
        ));
        $this->display();


    }


    public function order_success()
    {
        $btn = makeAlipayBtn(I('get.order_id'));
        // 设置页面信息
        $this->assign(array(
            'btn' =>$btn,
            '_page_title' => '下单成功',
            '_page_keywords' => '下单成功',
            '_page_description' => '下单成功',
        ));
        $this->display();

    }

    // 接收支付宝发来的支付成功的消息
    public function receive()
    {
        require('./alipay/notify_url.php');
    }

}