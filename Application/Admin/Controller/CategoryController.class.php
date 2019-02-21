<?php
namespace Admin\Controller;

use Think\Db;

class CategoryController extends BaseController
{

    public function lst()
    {
    	$model = D('Category');
    	$tree = $model->getTree();
//        dump($tree);
//    	die;

		// 设置页面中的信息
		$this->assign(array(
		    'tree'=>$tree,
			'_page_title' => '分类列表',
			'_page_btn_name' => '添加分类',
			'_page_btn_link' => U('add'),
		));

    	$this->display();
    }

    public function delete(){
        $model = D('Category');
        if(false!==$model->delete(I('get.id'))){
            $this->success('删除成功！',U('lst'));
        }else{
            $this->error('删除失败，原因：'.$model->getError());
        }

    }

    public function add(){

        $model = D('Category');
        if(IS_POST)
        {

            if($model->create(I('post.'), 1))
            {
                if($id = $model->add())
                {
                    $this->success('添加成功！', U('lst?p='.I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }


        $cats = $model->getTree();

        $this->assign(array(
            'cats' => $cats,
            '_page_title' => '添加分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ));

        $this->display();
    }

    public function edit(){

        $model = D('Category');

        if(IS_POST)
        {
            $model = D('Category');
            if($model->create(I('post.'), 2))
            {
                if($model->save() !== FALSE)
                {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        $this_cat = $model->find(I('get.id'));
        $children_ids = $model->getChildren(I('get.id'));
        $cats = $model->getTree();

        $this->assign(array(
            'children_ids' => $children_ids,
            'this_cat' => $this_cat,
            'cats' => $cats,
            '_page_title' => '添加分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ));
        $this->display();

    }
}