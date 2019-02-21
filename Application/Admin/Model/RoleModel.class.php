<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $insertFields = array('role_name');
	protected $updateFields = array('id','role_name');
	protected $_validate = array(
		array('role_name', 'require', '角色名称不能为空！', 1, 'regex', 3),
		array('role_name', '1,30', '角色名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('role_name', '', '角色名称已经存在！', 1, 'unique', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->
        alias('a')->
        field('a.*,GROUP_CONCAT(c.pri_name) pri_name')->
        join(
            'LEFT JOIN __ROLE_PRI__ b on a.id=b.role_id
             LEFT JOIN __PRIVILEGE__ c on b.pri_id=c.id'
        )->
        where($where)->
        group('a.id')->
        limit($page->firstRow.','.$page->listRows)->
        select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{

	}
	// 修改前
	protected function _before_update(&$data, $option)
	{

        $rp_model = D('role_pri');
	    //删除旧的记录
        $rp_model->where(array('role_id'=>array('eq',I('get.id'))))->delete();
        //添加角色权限关联
        $pri_ids = I('post.pri_id');
        foreach ($pri_ids as $pri_id){
            $rp_model->add(
                array(
                    'pri_id'=>$pri_id,
                    'role_id'=>I('get.id')
                )
            );
        }
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}

        //删除管理员与角色关联记录
        $ar_model = D('admin_role');
        $ar_model->where(array('role_id'=>array('eq',$option['where']['id'])))->delete();
        //删除角色与权限关联记录
        $rp_model = D('role_pri');
        $rp_model->where(array('role_id'=>array('eq',$option['where']['id'])))->delete();
	}

	//添加后
    protected function _after_insert($data, $options)
    {
        //添加角色权限关联
        $rp_model = D('role_pri');
        $pri_ids = I('post.pri_id');
        foreach ($pri_ids as $pri_id){
            $rp_model->add(
                array(
                    'pri_id'=>$pri_id,
                    'role_id'=>$data['id']
                )
            );
        }
    }

    /************************************ 其他方法 ********************************************/
}