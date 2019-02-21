<?php
namespace Admin\Controller;

use Think\Db;

class GoodsController extends BaseController
{
    //处理ajax返回商品属性
    public function ajaxGetAttr(){
        $type_id = I('get.type_id');
        $attr_model = D('Attribute');
        $data = $attr_model->where(array('type_id'=>array('eq',$type_id)))->select();
        echo json_encode($data);
    }

    //删除编辑商品属性时减少的属性
    public function ajaxDelAttr(){

        $gaid = I('get.gaid');
        $ga_model = D('goods_attr');
        $ga_model->where(array('id'=>array('eq',$gaid)))->delete();

        //删除与该属性相关的库存量
        $gn_model = D('goods_number');
        //商品id
        $goods_id = I('get.id');
        $gn_model->where(array(
            'goods_id'=>array('EXP',"=$goods_id AND FIND_IN_SET($gaid,goods_attr_id)")
        ))->delete();


    }

    public function goods_num(){

        $goods_id = I('get.id');
        $gn_model = D('goods_number');
        //取出当前商品的类型id
        $type_id = D('goods')->field('type_id')->find($goods_id);
        //处提交表单
        if(IS_POST){
            //先删除旧库存量
            $gn_model->where(array('goods_id'=>array('eq',$goods_id)))->delete();

            $attr_ids = I('post.attr_ids');
            $goods_num = I('post.goods_num');
            //判断该商品有一个组合属性有几个属性
            $step = count($attr_ids)/count($goods_num);

            $_i = 0;
            foreach ($goods_num as $gn) {
                $_goods_attr_id = array();
                for ($i = 0; $i < $step; $i++) {
                    $_goods_attr_id[] = $attr_ids[$_i];
                    $_i++;
                }
                //对商品属性组合排序，防止出现12,4和4,12不匹配
                sort($_goods_attr_id,SORT_NUMERIC);
                $_goods_attr_id = implode(',',$_goods_attr_id);

                $gn_model->add(array(
                    'goods_id' => $goods_id,
                    'goods_number' =>$gn,
                    'goods_attr_id'=>$_goods_attr_id
            ));

            }
            $this->success('设置成功',U('goods_num?id='.$goods_id));
            exit;
        }


        $ga_model = D('goods_attr');
        $_data = $ga_model->alias('a')->
                        join('LEFT JOIN __ATTRIBUTE__ b on a.attr_id=b.id
                           ')->
                        where(array('b.attr_type'=>array('eq','可选'),'b.type_id'=>array('eq',$type_id['type_id'])))
                        ->field('a.*,b.attr_name')
                        ->select();

        $data = array();
        foreach($_data as $k=>$v){
            $data[$v['attr_name']][] = $v;

        }


        //取出已有的库存量
        $gn_list = $gn_model->where(array('goods_id'=>array('eq',$goods_id)))->select();

        $this->assign(
            array(
                'gn_list' => $gn_list,
                'data' => $data,
                '_page_title'=>'库存量',
                '_page_btn_name'=>'商品列表',
                '_page_btn_link'=>U('lst'),
            )
        );
        $this->display();


    }


	// 显示和处理表单
	public function add()
	{
   		// 判断用户是否提交了表单
		if(IS_POST)
		{


			$model = D('goods');
			// 2. CREATE方法：a. 接收数据并保存到模型中 b.根据模型中定义的规则验证表单
			/**
			 * 第一个参数：要接收的数据默认是$_POST
			 * 第二个参数：表单的类型。当前是添加还是修改的表单,1：添加 2：修改
			 * $_POST：表单中原始的数据 ，I('post.')：过滤之后的$_POST的数据，过滤XSS攻击
			 */
			if($model->create(I('post.'), 1))
			{
				// 插入到数据库中
				if($model->add())  // 在add()里又先调用了_before_insert方法
				{
					// 显示成功信息并等待1秒之后跳转
					$this->success('操作成功！', U('lst'));
					exit;
				}
			}
			// 如果走到 这说明上面失败了在这里处理失败的请求
			// 从模型中取出失败的原因
			$error = $model->getError();
			// 由控制器显示错误信息,并在3秒跳回上一个页面
			$this->error($error);
		}

		//取出所有分类
        $cats = D('Category')->getTree();

        $this->assign(
            array(
                'cats' => $cats,
                '_page_title'=>'添加新商品',
                '_page_btn_name'=>'商品列表',
                '_page_btn_link'=>U('lst'),
            )
        );

		//获取所有会员类型
        $ml = D('MemberLevel');
        $mls = $ml->select();
        $this->assign('mls',$mls);
//        dump($mls);
//        die;
   		// 1.显示表单
   		$this->display();
	}
	
	// 商品列表页
	public function lst()
	{

	    $model = D('goods');
	    //在模型中定义方法获取数据和翻页
	    $data = $model->search();

        $this->assign('data',$data);

        $cats = D('category')->getTree();
        //分配layout变量
        $this->assign(
            array(
                'cats' => $cats,
                '_page_title'=>'商品列表',
                '_page_btn_name'=>'添加新商品',
                '_page_btn_link'=>U('add'),
            )
        );
		$this -> display();
	}

    public function edit()
    {
        // 判断用户是否提交了表单
        $model = D('goods');
        if(IS_POST)
        {


            if($model->create(I('post.'), 2))
            {
                // 插入到数据库中
                if($model->save()!==false)  //注意使用全等，因为save()成功是返回影响行数
                {
                    // 显示成功信息并等待1秒之后跳转
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }
            // 如果走到 这说明上面失败了在这里处理失败的请求
            // 从模型中取出失败的原因
            $error = $model->getError();
            // 由控制器显示错误信息,并在3秒跳回上一个页面
            $this->error($error);
        }
        // 1.显示表单
        $id = I('get.id');
        $data = $model->find($id);
        $this->assign('data',$data);

        //获取所有会员类型
        $ml = D('MemberLevel');
        $mls = $ml->select();
        //取出所有分类
        $cats = D('Category')->getTree();
        //获取商品所有扩展分类id
        $ext_cat_ids = D('goods_cat')->where(array('goods_id'=>array('eq',$id)))->select();
        //获取商品所有会员价格
        $mp = D('member_price')->where(array('goods_id'=>array('eq',$id)))->select();

        //获取商品所属类型的所有属性
        $type_id = $data['type_id'];
        $attr_model = D('attribute');
        $attr_list = $attr_model->alias('a')->field('a.*,b.attr_value,b.id goods_attr_id')->join("LEFT JOIN __GOODS_ATTR__ b on a.id=b.attr_id AND goods_id=$id")
            ->where(
            array('type_id'=>array('eq',$type_id))
            )->
            select();

        $this->assign(
            array(
                'attr_list' => $attr_list,
                'mp' => $mp,
                'ext_cat_ids' => $ext_cat_ids,
                'cats' => $cats,
                'mls' => $mls,
                '_page_title'=>'修改商品信息',
                '_page_btn_name'=>'商品列表',
                '_page_btn_link'=>U('lst'),
            )
        );
        $this->display();
    }

    public function delete(){
	    $model = D('goods');
	    if(false!==$model->delete(I('get.id'))){
	        $this->success('删除成功！',U('lst'));
        }else{
	        $this->error('删除失败，原因：'.$model->getError());
        }

    }

    public function test(){
	    $model = D('goods');
	    $model->getGoodsByCid(23);
    }
}