<?php
namespace Admin\Controller;

class AdminController extends BaseController
{
    public function add()
    {
    	if(IS_POST)
    	{

    		$model = D('Admin');
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

    	//取出所有角色
        $role_model = D("role");
    	$role_list = $role_model->select();

		// 设置页面中的信息
		$this->assign(array(
		    'role_list' => $role_list,
			'_page_title' => '添加管理员',
			'_page_btn_name' => '管理员列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin');
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
    	$model = M('Admin');
    	$data = $model->find($id);
    	$this->assign('data', $data);

        //取出当前管理员的所有角色
        $ar_model = D("admin_role");
        $role_ids = $ar_model->field('GROUP_CONCAT(role_id) role_id')->where(array('admin_id'=>array('eq',$id)))->group('admin_id')->find();
        //取出所有角色
        $role_model = D("role");
        $role_list = $role_model->select();

        // 设置页面中的信息
		$this->assign(array(
		    'role_ids' => $role_ids,
		    'role_list' => $role_list,
			'_page_title' => '修改管理员',
			'_page_btn_name' => '管理员列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '管理员列表',
			'_page_btn_name' => '添加管理员',
			'_page_btn_link' => U('add'),
		));
    	$this->display();
    }
}