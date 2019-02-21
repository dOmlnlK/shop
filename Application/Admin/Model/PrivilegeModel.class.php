<?php
namespace Admin\Model;
use Think\Model;
class PrivilegeModel extends Model 
{
	protected $insertFields = array('pri_name','module_name','controller_name','action_name','parent_id');
	protected $updateFields = array('id','pri_name','module_name','controller_name','action_name','parent_id');
	protected $_validate = array(
		array('pri_name', 'require', '权限名称不能为空！', 1, 'regex', 3),
		array('pri_name', '1,30', '权限名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('module_name', '1,30', '模块名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('controller_name', '1,30', '控制器名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('action_name', '1,30', '方法名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('parent_id', 'number', '上级权限Id必须是一个整数！', 2, 'regex', 3),
	);
	/************************************* 递归相关方法 *************************************/
	public function getTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
	/************************************ 其他方法 ********************************************/
	public function _before_delete($option)
	{
		// 先找出所有的子分类
		$children = $this->getChildren($option['where']['id']);
		// 如果有子分类都删除掉
		if($children)
		{
			$this->error = '有下级数据无法删除';
			return FALSE;
		}

        //删除角色与权限关联记录
        $rp_model = D('role_pri');
        $rp_model->where(array('pri_id'=>array('eq',$option['where']['id'])))->delete();


	}


	//判断当前用户是否有权限访问
    public function checkPri(){
	    $admin_id = session('id');

	    //如果当前用户是root直接返回true
	    if($admin_id==1)
	        return true;

	    $ar_model = D('admin_role');
	    $pri_ids = $ar_model->alias('a')->field('b.pri_id')->
        join("LEFT JOIN __ROLE_PRI__ b on a.role_id=b.role_id")->
        where(array('admin_id'=>array('eq',$admin_id)))->select();

	    //当前admin所拥有的所有权限的id集合
	    $_pri_ids = array();
        foreach ($pri_ids as $id){
            $_pri_ids[] = $id['pri_id'];
        }

        //当前页面的权限id
        $cpri = $this->field('id')->where(array(
            'module_name'=>array('eq',MODULE_NAME),
            'controller_name'=>array('eq',CONTROLLER_NAME),
            'action_name'=>array('eq',ACTION_NAME)
        ))->find();

        if(in_array($cpri['id'],$_pri_ids))
            return true;
        else
            return false;


    }


    //获取当前管理员的菜单
    public function getMenu(){
	    $admin_id = session('id');
	    //先取出当前用户的所有权限
        if($admin_id==1){
            $pri_model = D('privilege');
            $pri_data = $pri_model->select();

        }else{
            //如果不是root用户，先取出用户所有角色，再根据角色取出权限
            $ar_model = D('admin_role');
            $pri_data = $ar_model->alias('a')
                ->field('DISTINCT c.*')
                ->join('LEFT JOIN __ROLE_PRI__ b on b.role_id=a.role_id
                    LEFT JOIN __PRIVILEGE__ c on c.id=b.pri_id')
                ->where(array(
                    'a.admin_id'=>array('eq',$admin_id)
                ))
                ->select();
        }


        //找出前两级权限
        $btns =array();
        foreach ($pri_data as $k=>$v){
            //如果是第一级权限则再找一层
            if($v['parent_id']==0){
                foreach ($pri_data as $k1=>$v1){
                    if($v1['parent_id']==$v['id']){
                        $v['children'][] = $v1;
                    }
                }
                $btns[] = $v;
            }

        }
        return $btns;

    }
}