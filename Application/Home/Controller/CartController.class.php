<?php
namespace Home\Controller;
use Think\Controller;



class CartController extends Controller
{
  public function add(){

      $cart_model = D('cart');

      if($cart_model->create(I('post.'),1)){
          if($cart_model->add()){
                redirect(U('lst'));
          }else{
                  $this->error($cart_model->getError());
          }
      }else{

          $this->error($cart_model->getError());
      }



  }

  public function lst(){

     $cart_model = D('cart');
     $data = $cart_model->cart_list();

     $this->assign(
         array('data'=>$data,
             '_page_title' => '购物车列表')
     );

     $this->display();


  }

  public function ajaxCartList(){

      $cart_model = D('cart');
      $data = $cart_model->cart_list();

      echo json_encode($data);

  }

}