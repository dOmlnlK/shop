<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','check_code');
	protected $updateFields = array('id','username','password','cpassword');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 1),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('cpassword', 'password', '两次密码输入不一致!', 1, 'confirm', 3),
	);

	//创建为登录表单验证的验证规则
    public  $_login_validate = array(
        array('username', 'require', '用户名不能为空！', 1),
        array('password', 'require', '密码不能为空！', 1),
        array('check_code', 'require', '验证码不能为空！', 1),
        array('check_code', 'check_verify', '验证码不正确！', 1,'callback'),
    );
    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    public function login(){
        //从模型中取出账号密码

        $username = I('post.username');
        $password = I('post.password');
        //判断是否存在该用户
        $user = $this->where(array('username'=>array('eq',$username)))->find();
        if($user){
            if($user['password'] == md5($password)){
                //说明登录成功，设置session
                session('id',$user['id']);
                session('user',$user['username']);
                return True;

            }
            else{
                $this->error = '密码输入错误！';
                return false;
            }

        }else{
            $this->error = '用户名不存在！';
            return false;

        }



    }


	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($username = I('get.username'))
			$where['username'] = array('like', "%$username%");
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->field('a.*,GROUP_CONCAT(c.role_name) role_name')->
            join('LEFT JOIN __ADMIN_ROLE__ b on a.id=b.admin_id
                  LEFT JOIN __ROLE__ c on c.id=b.role_id')->
        where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	    $data['password'] = md5($data['password']);
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
	    if($data['password'])
            $data['password'] = md5($data['password']);
	    else
	        unset($data['password']);

        //添加角色管理关联
        $rp_model = D('admin_role');
        $rp_model->where(array('admin_id'=>array('eq',I('get.id'))))->delete();
        $role_ids = I('post.role_id');
        foreach ($role_ids as $role_id) {
            $rp_model->add(
                array(
                    'admin_id' => I('get.id'),
                    'role_id' => $role_id
                )
            );
        }
    }

	// 删除前
	protected function _before_delete($option)
	{
	    if($option['where']['id']==1){
	        $this->error = '超级管理员不能被删除！';
	        return false;
        }


		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}

		//删除管理员与角色关联记录
        $ar_model = D('admin_role');
		$ar_model->where(array('admin_id'=>array('eq',$option['where']['id'])))->delete();
	}
	/************************************ 其他方法 ********************************************/

    //添加后
    protected function _after_insert($data, $options)
    {
        //添加角色管理关联
        $rp_model = D('admin_role');
        $role_ids = I('post.role_id');
        foreach ($role_ids as $role_id) {
            $rp_model->add(
                array(
                    'admin_id' => $data['id'],
                    'role_id' => $role_id
                )
            );
        }
    }

}

