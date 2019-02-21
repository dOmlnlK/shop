<?php
namespace Home\Controller;
use Think\Controller;


class CommentController extends Controller
{
  public function add(){
     $model = D('comment');

     if(IS_POST){
         if($model->create(I('post.'),1)){

             if($id = $model->add()){
                 $this->success(array(
                     'id' => $id,
                     'content'=>I('post.content'),
                     'star'=>I('post.star'),
                     'addtime'=>date('Y-m-d H:i:s'),
                     'member'=>session('member'),
                     'avatar'=>'/Public/Home/images/user2.jpg'   //由于没做头像功能，先固定
                 ));
             }

             else{
                 $this->error($model->getError());
                 exit;
             }


         }
         else{
             $this->error($model->getError());
             exit;

         }
     }



  }

  public function ajaxGetComment(){
      $model = D('comment');

      $gid = I('id');

      $data = $model->search($gid);

      echo json_encode($data);


  }

  public function reply()
  {

      $model = D('comment_reply');

      if (IS_POST) {
          if ($model->create(I('post.'), 1)) {

              if ($id = $model->add()) {
                  $this->success(array(
                      'id' => $id,
                      'content' => I('post.content'),
                      'addtime' => date('Y-m-d H:i:s'),
                      'member' => session('member'),
                      'avatar' => '/Public/Home/images/user2.jpg'   //由于没做头像功能，先固定
                  ));
              } else {
                  $this->error($model->getError());
                  exit;
              }


          } else {
              $this->error($model->getError());
              exit;

          }


      }

  }

}